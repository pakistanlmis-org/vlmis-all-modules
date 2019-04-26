        function FilterData(){
                
           var prov = $("#province option:selected").text();
          
           var features = province.features;
           var districtfeatures = district.features;
           
             for(var i=0; i< features.length; i++)
                    {
                          features[i].style = '';
                    }
                     province.redraw();
             
               for(var i=0; i< districtfeatures.length; i++) 
                    {
                           districtfeatures[i].style = '';
                    }    
                 district.redraw();
        
          if(prov == "National")
             {
                map.setOptions({maxExtent: restricted});
                map.setOptions({restrictedExtent: restricted});
                map.events.register("zoomend", map, zoomChanged);
                map.events.register("zoomend", map, zoomRestrict);
                map.zoomToExtent(bounds); 
             }
            else{
                
                  map.setOptions({maxExtent: null});
                  map.setOptions({restrictedExtent: null}); 
                  map.events.register("zoomend", map, zoomChanged);
                  map.events.register("zoomend", map, zoomRestrict);

                    for(var i=0; i< features.length; i++)
                    {
                            if(features[i].attributes.province_name != prov)
                            {
                              features[i].style = {display:'none'};
                              
                            }
                            else
                            {
                                map.zoomToExtent(features[i].geometry.getBounds()); 
                            }    
                    }
                
                      
                       if(features[3].attributes.province_name == prov)
                            {  
                                 map.events.remove("zoomend", map, zoomChanged);
                                 map.events.remove("zoomend", map, zoomRestrict);
                                // features[3].style = {display:'none'};

                                var isb = new OpenLayers.Bounds(72.6601,33.472,73.4703,33.9187);
                                 isb.transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));
                                 map.zoomToExtent(isb);
                            } 
                          
                    
                    province.redraw();

                    for(var i=0; i< districtfeatures.length; i++) 
                    {
                           if(districtfeatures[i].attributes.province_name != prov)
                           {  
                             districtfeatures[i].style = {display:'none'};
                           }   
                    }    
                     district.redraw();  
               }
             
            
            }
            
            
            
            
                function zoomChanged()
                {
                    var zoom = map.getZoom();
                    if(zoom >= 1){
                        vLMIS.styleMap = vlMIS_label_style;
                        vLMIS.redraw();
                    }
                    else{
                        vLMIS.styleMap = vlMIS_style;
                        vLMIS.redraw();
                    }
                }

                var ext;
                function zoomRestrict(){
                         var x = map.getZoom();

                          if(x == 3){
                              ext = map.getExtent();
                          }
                          if(x >= 3)
                          {
                               map.zoomToExtent(ext);
                          }
                      }
                      
                      
                      
                      
                      
                      
                      
                      
          function FilterCCData(){
                
           var prov = $("#province option:selected").text();
          
           var features = province.features;
           var districtfeatures = district.features;
           
             for(var i=0; i< features.length; i++)
                    {
                          features[i].style = '';
                    }
                     province2.redraw();
             
               for(var i=0; i< districtfeatures.length; i++) 
                    {
                           districtfeatures[i].style = '';
                    }    
                 district2.redraw();
        
          if(prov == "National")
             {
                map2.setOptions({maxExtent: restricted});
                map2.setOptions({restrictedExtent: restricted});
                map2.events.register("zoomend", map2, zoomChangedCC);
                map2.events.register("zoomend", map2, zoomRestrictCC);
                map2.zoomToExtent(bounds); 
             }
            else{
                
                  map2.setOptions({maxExtent: null});
                  map2.setOptions({restrictedExtent: null}); 
                  map2.events.register("zoomend", map2, zoomChangedCC);
                  map2.events.register("zoomend", map2, zoomRestrictCC);

                    for(var i=0; i< features.length; i++)
                    {
                            if(features[i].attributes.province_name != prov)
                            {
                              features[i].style = {display:'none'};
                              
                            }
                            else
                            {
                                map2.zoomToExtent(features[i].geometry.getBounds()); 
                            }    
                    }
                
                      
                       if(features[3].attributes.province_name == prov)
                            {  
                                 map2.events.remove("zoomend", map2, zoomChangedCC);
                                 map2.events.remove("zoomend", map2, zoomRestrictCC);
                                 features[3].style = {display:'none'};

                                 var isb = new OpenLayers.Bounds(72.6601,33.472,73.4703,33.9187);
                                 isb.transform(new OpenLayers.Projection("EPSG:4326"),new OpenLayers.Projection("EPSG:900913"));
                                 map2.zoomToExtent(isb);
                            } 
                          
                    
                    province2.redraw();

                    for(var i=0; i< districtfeatures.length; i++) 
                    {
                           if(districtfeatures[i].attributes.province_name != prov)
                           {  
                             districtfeatures[i].style = {display:'none'};
                           }   
                    }    
                     district2.redraw();  
               }
             
            
            }
            
            
            
            
                function zoomChangedCC()
                {
                    var zoom = map2.getZoom();
                    if(zoom >= 1){
                        vLMIS2.styleMap = vlMIS_label_style;
                        vLMIS2.redraw();
                    }
                    else{
                        vLMIS2.styleMap = vlMIS_style;
                        vLMIS2.redraw();
                    }
                }

                var ext;
                function zoomRestrictCC(){
                         var x = map2.getZoom();

                          if(x == 2){
                              ext = map2.getExtent();
                          }
                          if(x > 2)
                          {
                               map2.zoomToExtent(ext);
                          }
                      }