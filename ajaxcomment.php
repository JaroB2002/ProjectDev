<!DOCTYPE html>
<html>
<head>
	<title>AJAX-commentaar</title>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
	<h1>Gebruikerscommentaar:</h1>
	<div id="commentaar"></div>

	<form>
		<label for="opmerking">Typ hier uw opmerking:</label><br>
		<textarea id="opmerking" name="opmerking"></textarea><br>
		<button type="button" id="verzend-knop">Verzend</button>
	</form>

	<script>
		$(document).ready(function(){
			// Haal commentaar op uit bestand en toon het op de pagina
			$.ajax({
				type: "GET",
				url: "commentaar.txt",
				dataType: "text",
				success: function(response){
					$("#commentaar").html(response);
				}
			});

			// Verwerk het verzenden van het formulier wanneer op de knop wordt geklikt
			$("#verzend-knop").click(function(){
				// Haal de ingevoerde opmerking uit het tekstvak
				var nieuweOpmerking = $("#opmerking").val();

				// Voeg de nieuwe opmerking toe aan het commentaarbestand
				$.ajax({
					type: "POST",
					url: "voeg-opmerking-toe.php",
					data: {opmerking: nieuweOpmerking},
					success: function(){
						// Laad het bijgewerkte commentaar opnieuw en toon het op de pagina
						$.ajax({
							type: "GET",
							url: "commentaar.txt",
							dataType: "text",
							success: function(response){
								$("#commentaar").html(response);
								// Wis het tekstvak na verzending van de opmerking
								$("#opmerking").val("");
							}
						});
					}
				});
			});
		});
	</script>
</body>
</html>
