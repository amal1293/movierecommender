var flag = 1;
function changeColor(numb){
	if(flag==1){
		for(var i=1;i<=numb;i++){
			var star = document.getElementById('star'+i);
			star.src = 'rating/color.jpg';
		}
		var rating = document.getElementById('rating');
		rating.innerHTML = numb+'.0';
	}
}

function revertColor(numb){
	if(flag==1){
		for(var i=1;i<=numb;i++){
			var star = document.getElementById('star'+i);
			star.src = 'rating/white.jpg';
		}
		var rating = document.getElementById('rating');
		rating.innerHTML = '0.0';
	}

}
function fixcolor(numb){
	if(flag==1){
		for(var i=1;i<=numb;i++){
			var star=document.getElementById('star'+i);
			star.src='rating/color.jpg';
		}
		var rating = document.getElementById('rating');
		rating.innerHTML = numb+'.0';
		flag=0;
		var message = document.getElementById('message');
		message.innerHTML = "Your Rating: ";
	}
}

function storerating(mid,numb){
	var xmlhttp;
	if(window.XMLHttpRequest)
		xmlhttp = new XMLHttpRequest();
	else
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	xmlhttp.open('GET','storerating.php?mid='+mid+'&rating='+numb,true);
	xmlhttp.send();
}