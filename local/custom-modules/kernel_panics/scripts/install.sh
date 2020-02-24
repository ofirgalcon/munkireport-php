#!/bin/bash

# kernel_panics controller
CTL="${BASEURL}index.php?/module/kernel_panics/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/kernel_panics" -o "${MUNKIPATH}preflight.d/kernel_panics"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/kernel_panics"

	# Set preference to include this file in the preflight check
	setreportpref "kernel_panics" "${CACHEPATH}kernel_panics.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/kernel_panics"

	# Signal that we had an error
	ERR=1
fi
