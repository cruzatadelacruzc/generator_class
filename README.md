**Generator of structure classes**

**Input data**

*Project structure*

    Controller /Module
    
*Bundle name*

    WooBundle   
    
**Ouput**
    
    project
       |-Controller(folder)
            |-Modules(folder)
              |-Module(folder)
                    |-WooBundleCompareSMTModuleController(class)
                    |-WooBundleCookiesSMTModuleController(class)  
                    |-WooBundleCrossSaleSMTModuleController(class)  
                    |-WooBundleFastbuySMTModuleController(class)  
                    |-WooBundleRatingSMTModuleController(class)  
              |-WooBundleBaseStoreController(class)
              |-WooBundleSmtBaseStoreController(class)
              |-WooBundleSMTModuleController(class) 
              
*See* file config.xml. 

*Note*: If you accidentally delete the config.xml file, you can duplicate and rename the backup file config_example.xml             