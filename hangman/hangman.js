/*var xmlhttp;
if(window.XMLHttpRequest)
	xmlhttp = new XMLHttpRequest();
else
	xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
function checkvalue(id,key){
	
	var triesleft = 8-src;
	xmlhttp.open('GET','checkhangman.php?id='+id+'&key='+key+'&tries='+triesleft,true);
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			var text = xmlhttp.responseText;
			var clicked = document.getElementById(key);
			clicked.disabled = true;
			if(text.indexOf('no') == 0){
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
					//console.log('letter'+positions[i]);
					var el=document.getElementById('letter'+positions[i]);
					el.innerHTML = key;
					var all = document.getElementsByClassName('letters');
					var finished = 1;
					for(var j=0;j<all.length;j++){
						if(all[j].innerHTML == "__")
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
}*/

/*function solvehman(id,key){
	xmlhttp.open('GET','checkhangman.php?sid='+id,false);
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.status == 200 && xmlhttp.readyState == 4){
			var text = xmlhttp.responseText;
			//alert(text);
			var all = document.getElementsByClassName('letters');
			//alert(text.length+'  '+all.length);
			var k=0;
			for(var i=0;i<text.length;i++)
				if(text[i]!= " ")
					//alert(all[k].innerHTML);
					all[K].innerHTML = text[i];
		}
	}
	xmlhttp.send();
}*/

function checkvalue(key){
	var clicked = document.getElementById(key);
	clicked.disabled = true;
	ispresent = 0;
	for(var i=0;i<ans.length;i++){
		//alert(ans[i]+' and '+key.toLowerCase());
		if(ans[i] == key.toLowerCase()){
			var letter = document.getElementById('letter'+i);
			letter.innerHTML = key;
			ispresent = 1;
			var all = document.getElementsByClassName('letters');
			var finished = 1;
			for(var j=0;j<all.length;j++)
				if(all[j].innerHTML == '__')
					finished = 0;
			if(finished == 1){
				var blanks = document.getElementById('blanks');
				blanks.innerHTML = "You Win!";
			}
		}
	}
	if(ispresent == 0){
		var hangman = document.getElementById('hangman');
		var src = parseInt(hangman.src.replace(/^\D+/g,'')) + 1;
		if(src<=7)
			hangman.src = 'images/'+src+'.png';
		else{
			var buttons = document.getElementsByClassName('keys');
			for(var j=0;j<buttons.length;j++)
				buttons[j].disabled = true;
			var blanks = document.getElementById('blanks');
			blanks.innerHTML = 'You Lose<br/>Movie:'+ans.toUpperCase();
		}
	}
}

function solvehman(){
	var blanks = document.getElementById('blanks');
	blanks.innerHTML = ans.toUpperCase();
}