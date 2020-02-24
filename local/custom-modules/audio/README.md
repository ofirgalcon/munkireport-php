Audio module
==============

Provides the status of Audio devices.

Data can be viewed under the Audio Devices tab on the client details page or using the Audio list view.

Based on Thunderbolt module by tuxudo

Table Schema
---
* name - varchar(255) - Name of the Audio device
* device_type - varchar(255) - Audio device type
* driver_installed - boolean - Is driver installed
* link_speed - varchar(255) - Link speed
* link_width - varchar(255) - Link width
* device_name - varchar(255) - Device name
* revision_id - varchar(255) - Revision ID
* slot_name - varchar(255) - Slot name
* subsystem_id - varchar(255) - Subsystem ID
* subsystem_vendor_id - varchar(255) - Subsystem vendor ID
* vendor_id - varchar(255) - Vendor ID
