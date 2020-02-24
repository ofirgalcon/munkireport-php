SSD Service Program module
================
This module serves a specific need to track systems that are eligible for the 13-inch MacBook Pro (non Touch Bar) Solid-State Drive Service Program https://www.apple.com/support/13-inch-macbook-pro-solid-state-drive-service-program/ 

The following information is stored in the ssd_service_program table:

* needs_service
    - String
* ssd_model
    - String
* ssd_revison
    - String
    
From examples of repaired vs non-repaired eligible and non-eligible machines:
```
Model:
    "MacBook Pro (13-inch, 2017, Two Thunderbolt 3 ports)
 
 
Serial Number:
    The 4th character is either a 'V' (2017 (2nd half)) or 'W' (2018 (1st half))
 
SSD Revision:
    NON-SERVICED - CXS4JA0Q (the J is the determining factor)
    SERVICED - CXS4LA0Q (the L is the determining factor)
 
SSD Model Affected:
    APPLE SSD SM0256L
```
