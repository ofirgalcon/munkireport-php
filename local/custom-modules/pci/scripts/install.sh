#!/bin/bash

# pci controller
CTL="${BASEURL}index.php?/module/pci/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/pci" -o "${MUNKIPATH}preflight.d/pci"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/pci"

	# Set preference to include this file in the preflight check
	setreportpref "pci" "${CACHEPATH}pci.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/pci"

	# Signal that we had an error
	ERR=1
fi
