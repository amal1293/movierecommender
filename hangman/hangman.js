var xmlhttp;
if(window.XMLHttpRequest)
	xmlhttp = new XMLHttpRequest();
else
	xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
function checkvalue(id,key){
	xmlhttp.open('GET','checkhangman.php?id='+id+'&key='+key,true);
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			var text = xmlhttp.responseText;
			var clicked = document.getElementById(key);
			clicked.disabled = true;
			if(text.indexOf('no') != -1){
				var hangman = document.getElementById('hangman');
				src = parseInt(hangman.src.replace(/^\D+/g,'')) + 1;
				if(src <= 7)
					hangman.src = 'images/'+src+'.png';
				else{
					alert("You Lose!");
					location.reload();
				}
			}
			else{
				var positions = text.split(',');
				for(var i=0;i<positions.length;i++){
					console.log('letter'+positions[i]);
					var el=document.getElementById('letter'+positions[i]);
					el.innerHTML = key;
					var all = document.getElementsByClassName('letters');
					var finished = 1;
					for(var j=0;j<all.length;j++){
						if(all[j].innerHTML == "___")
							finished = 0;
					}
					if(finished == 1){
						alert("Congratulations. You Win!");
						location.reload();
					}
				}
			}
		}
	}
	xmlhttp.send();
}