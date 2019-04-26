 $(function(){
		// Multiselect    
		if($("#ms").length > 0)
			$("#ms").multiSelect({
				afterSelect: function(value, text){
					//notify('Multiselect','Selected: '+text+'['+value+']');
				},
				afterDeselect: function(value, text){
					//notify('Multiselect','Deselected: '+text+'['+value+']');
				}
			});
		
		
		if($("#msc").length > 0 || $("#compliance").length > 0){
			$("#msc").multiSelect({
				selectableHeader: "<div class='multipleselect-header'>Un-assigned Warehouses</div>",
				selectedHeader: "<div class='multipleselect-header'>Assigned Warehouses</div>",
				afterSelect: function(value, text){
				   // notify('Multiselect','Selected: '+text+'['+value+']');
				},
				afterDeselect: function(value, text){
					//notify('Multiselect','Deselected: '+text+'['+value+']');
				}            
			});      
		}
	})
   