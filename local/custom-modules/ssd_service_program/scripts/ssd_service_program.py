#!/usr/bin/python

"""
SSD service program reporting tool to determine which machines need to be serviced according to https://www.apple.com/support/13-inch-macbook-pro-solid-state-drive-service-program/
"""

import subprocess
import os
import plistlib
import sys
import objc
from Foundation import NSBundle

IOKit_bundle = NSBundle.bundleWithIdentifier_('com.apple.framework.IOKit')

functions = [("IOServiceGetMatchingService", b"II@"),
             ("IOServiceMatching", b"@*"),
             ("IORegistryEntryCreateCFProperty", b"@I@@I"),
            ]

objc.loadBundleFunctions(IOKit_bundle, globals(), functions)

def io_key(keyname):
    return IORegistryEntryCreateCFProperty(IOServiceGetMatchingService(0, IOServiceMatching("IOPlatformExpertDevice")), keyname, None, 0)

def get_hardware_serial():
    return io_key("IOPlatformSerialNumber")

def get_ssd_info():
    '''Uses system_profiler command to get NVMe data.'''
    cmd = ['/usr/sbin/system_profiler', 'SPNVMeDataType', '-xml']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()
    try:
        plist = plistlib.readPlistFromString(output)
        # system_profiler xml is an array
        ssd_dict = plist[0]
        items = ssd_dict['_items']
        return items
    except Exception:
        return {}

def flatten_ssd_info(array):
    '''Un-nest plist info, return array with objects with relevant keys'''
    out = []
    for obj in array:
        ssd = {}
        for item in obj:
            if item == '_items':
                out = out + flatten_ssd_info(obj['_items'])
            elif item == 'device_model':
                ssd['model'] = obj[item]
            elif item == 'device_revision':
                ssd['revision'] = obj[item]        
        if ssd is not None:
            out.append(ssd)
        
    return out
    
def validate_SN():
    serial_number = get_hardware_serial()
    # If the serial number's 4th character is V or W it is Late 2017 or Early 2018
    #  reference: https://beetstech.com/blog/decode-meaning-behind-apple-serial-number
    if serial_number[3] in {"V", "W"}:
        return True
    else: 
        return False
 
def main():
    """Main"""
    # Create cache dir if it does not exist
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    if not os.path.exists(cachedir):
        os.makedirs(cachedir)

    # Skip manual check
    if len(sys.argv) > 1:
        if sys.argv[1] == 'manualcheck':
            print 'Manual check: skipping'
            exit(0)
    
    # Get results
    result = dict()

    if not validate_SN():
        result = {}
    else:
        info = get_ssd_info()
        ssd_specs = flatten_ssd_info(info)
        # Specific model is vulnerable
        if 'SM0256L' in ssd_specs[0].get("model"):
            # Specific revision of the ssd firmware is vulnerable
            if 'CXS4JA0Q' in ssd_specs[0].get("revision"):
                result['needs_service'] = "True"
            else:
                result['needs_service'] = "False"
        else:
            result['needs_service'] = "False"
    
    result['ssd_model'] = ssd_specs[0].get("model")
    result['ssd_revision'] = ssd_specs[0].get("revision")
    
    # Write results to cache
    output_plist = os.path.join(cachedir, 'ssd_service_program.plist')
    plistlib.writePlist(result, output_plist)

if __name__ == "__main__":
    main()
