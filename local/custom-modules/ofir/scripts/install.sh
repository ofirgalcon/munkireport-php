#!/bin/bash

# ofir controller
CTL="${BASEURL}index.php?/module/ofir/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/ofir.sh" -o "${MUNKIPATH}preflight.d/ofir.sh"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/ofir.sh"

	# Set preference to include this file in the preflight check
	setreportpref "ofir" "${CACHEPATH}ofir.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/ofir.sh"

	# Signal that we had an error
	ERR=1
fi
