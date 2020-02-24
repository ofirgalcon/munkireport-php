#!/bin/bash

# Remove kernel_panics script
rm -f "${MUNKIPATH}preflight.d/kernel_panics"

# Remove kernel_panics.plist cache file
rm -f "${MUNKIPATH}preflight.d/cache/kernel_panics.plist"
