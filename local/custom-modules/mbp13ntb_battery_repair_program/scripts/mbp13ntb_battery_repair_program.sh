#!/bin/bash
# set -e
# set -u
# set -o pipefail

# Adapted from https://www.jamf.com/jamf-nation/discussions/32400/battery-recall-for-15-mid-2015-mbp#responseChild186454
#   for use with MunkiReport.
# This script will continue to evaluate the machine once a day if it is deemed eligible.
# Machines confirmed not eligible will only run once.

# Skip manual check
if [ "$1" = 'manualcheck' ]; then
	echo 'Manual check: skipping'
	exit 0
fi

# Create cache dir if it does not exist
DIR=$(dirname "$0")
mkdir -p "$DIR/cache"
mbp13ntb_battery_repair_program_file="$DIR/cache/mbp13ntb_battery_repair_program.txt"

dateStamp () {
    mkdir -p "$DIR"/cache
    echo $(date -j +'%Y%m%d') > "$DIR"/cache/.appleRecall032018DateStamp
}

dateCheck () {
    if [[ ! -f "$DIR"/cache/.appleRecall032018DateStamp ]]; then
        echo 0
    else
        echo $(cat "$DIR"/cache/.appleRecall032018DateStamp)
    fi
}

createRecallCheckDone () {
    mkdir -p "$DIR"/cache
    echo $1 > "$DIR"/cache/.appleRecall032018CheckDone
}

stillEligible () {
    if [[ -e "$DIR"/cache/.appleRecall032018CheckDone ]]; then
        if [[ $(grep -c "E01-Ineligible" "$DIR"/cache/.appleRecall032018CheckDone) -ge 1 ]]; then
            echo "Eligibility: E01-Ineligible" >> "$mbp13ntb_battery_repair_program_file"
            return 1
        elif [[ $(grep -c "FE01-EmptySerial" "$DIR"/cache/.appleRecall032018CheckDone) -ge 1 ]]; then
            echo "Eligibility: FE01-EmptySerial" >> "$mbp13ntb_battery_repair_program_file"
            return 1
        elif [[ $(grep -c "FE02-InvalidSerial" "$DIR"/cache/.appleRecall032018CheckDone) -ge 1 ]]; then
            echo "Eligibility: FE02-InvalidSerial" >> "$mbp13ntb_battery_repair_program_file"
            return 1
        else
            return 0
        fi
    fi
    return 0
}

postURL="https://qualityprograms.apple.com/lookup/032018"
quotSerial=$(ioreg -l | grep IOPlatformSerialNumber | awk '{print $4}')
quotGUID='"'$(uuidgen | tr "[:upper:]" "[:lower:]")'"'
modelID=$(system_profiler SPHardwareDataType | grep "Model Identifier" | awk '{print $3}')
todayDate=$(date -j +'%Y%m%d')
lastCheckDate=$(dateCheck)
daysSinceCheck=$(expr $todayDate - $lastCheckDate)

# Check once a day, if the last check is older than today, proceed
if [[ $daysSinceCheck -gt 6 ]]; then
    echo "LastCheck: $(date "+%s")" > "$mbp13ntb_battery_repair_program_file"
    # Be nice to qualityprograms.apple.com and only query valid models by pre-qualifying:
    if [[ "$modelID" == "MacBookPro13,1" || "$modelID" == "MacBookPro14,1" ]]; then
        # 12 charlen for all serials in affected years (14 incl quote)
        # 36 charlen for all guids (38 incl quote)
        if [[ "${#quotSerial}" -eq 14 ]]; then
            if [[ "${#quotGUID}" -eq 38 ]]; then
                if stillEligible; then
                    postData="{\"serial\":$quotSerial,\"GUID\":$quotGUID}"
                    resp=$(curl -d "$postData" -H "Content-Type: application/json" -X POST "$postURL")
                    if [[ "$resp" == *'"E00"'* ]]; then
                        echo "Eligibility: E00-Eligible" >> "$mbp13ntb_battery_repair_program_file"
                        createRecallCheckDone "E00-Eligible"
                        dateStamp
                    elif [[ "$resp" == *'"E01"'* ]]; then
                        echo "Eligibility: E01-Ineligible" >> "$mbp13ntb_battery_repair_program_file"
                        createRecallCheckDone "E01-Ineligible"
                        dateStamp
                    elif [[ "$resp" == *'"E99"'* ]]; then
                        echo "Eligibility: E99-ProcessingError" >> "$mbp13ntb_battery_repair_program_file"
                        dateStamp
                        # createRecallCheckDone "E99-ProcessingError"
                    elif [[ "$resp" == *'"FE01"'* ]]; then
                        echo "Eligibility: FE01-EmptySerial" >> "$mbp13ntb_battery_repair_program_file"
                        createRecallCheckDone "FE01-EmptySerial"
                        dateStamp
                    elif [[ "$resp" == *'"FE02"'* ]]; then
                        echo "Eligibility: FE02-InvalidSerial" >> "$mbp13ntb_battery_repair_program_file"
                        createRecallCheckDone "FE02-InvalidSerial"
                        dateStamp
                    elif [[ "$resp" == *'"FE03"'* ]]; then
                        echo "Eligibility: FE03-ProcessingError" >> "$mbp13ntb_battery_repair_program_file"
                        dateStamp
                        # createRecallCheckDone "FE03-ProcessingError"
                    else
                        echo "Eligibility: Err1-UnexpectedResponse" >> "$mbp13ntb_battery_repair_program_file"
                        dateStamp
                    fi
                fi
            else
                echo "Eligibility: Err2-NotQueried-InvalidGuidLength" >> "$mbp13ntb_battery_repair_program_file"
                dateStamp
            fi
        else
            echo "Eligibility: Err3-NotQueried-InvalidSerialLength" >> "$mbp13ntb_battery_repair_program_file"
            dateStamp
        fi
    else
        echo "Eligibility: Msg1-IneligibleModel" >> "$mbp13ntb_battery_repair_program_file"
    fi
fi


