const URL_REGEX = /^((http|https):\/\/)?[a-z0-9.\-_]{2,}\.[a-z0-9]{2,}$/;

function validateURL( url: string ): boolean
{
	return URL_REGEX.test( url );
}

$( () =>
   {
	   const
			   urlInput = $( "#url" ),
			   errorMessage = $( "#regex-error" ),
			   submit = $( "#submit" );
	
	   urlInput.on( "changed paste keyup", () =>
	   {
		   if( validateURL( <string>urlInput.val() ) )
			   errorMessage.hide();
	   } );
	
	   submit.on( "click", () =>
	   {
		   if( !validateURL( <string>urlInput.val() ) )
		   {
			   errorMessage.show();
			   return false;
		   }
	   } );
   } );