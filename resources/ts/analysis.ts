$( () =>
   {
	   const generateButton = $( "#generate-xlsx" );
	
	   generateButton.on( "click", () =>
	   {
		   const
				   dataMap = [],
				   exists = generateButton.attr( "data-exists" ),
				   status = generateButton.attr( "data-status" ),
				   filesize = generateButton.attr( "data-filesize" ),
				   maps = generateButton.attr( "data-sitemaps" ),
				   hosts = generateButton.attr( "data-hosts" );
		
		   dataMap.push( exists, status, filesize, maps, hosts );
		   
		   console.log(dataMap);
		
		   let data = JSON.stringify( dataMap );
		
		   $.ajax(
				   {
					   url: "/toxlsx.php",
					   method: "post",
					   data: {data},
					   success: ( response ) =>
					   {
						   window.location = response;
					   }
				   }
		   );
	   } );
   } );