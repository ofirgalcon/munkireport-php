#!/bin/bash

# audio controller
CTL="${BASEURL}index.php?/module/audio/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/audio" -o "${MUNKIPATH}preflight.d/audio"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/audio"

	# Set preference to include this file in the preflight check
	setreportpref "audio" "${CACHEPATH}audio.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/audio"

	# Signal that we had an error
	ERR=1
fi
