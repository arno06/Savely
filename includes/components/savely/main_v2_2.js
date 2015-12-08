var main  = (function(){

	function toggleStatsHandler(e)
	{
		if(e)
		{
			e.preventDefault();
			e.stopPropagation();
			e.stopImmediatePropagation();
		}
		document.querySelector('#Dabox .details_product .stats').classList.toggle('hidden');
	}

	function dataReadyForChartHandler(pResponse)
	{
		var w = "535";
		var h = "275";
        console.table(pResponse.responseJSON);
        var data = [];
        var label = [];
        var item = {};
        for(var i = 0, max = Math.min(pResponse.responseJSON.length, 7); i<max;i++)
        {
            item = pResponse.responseJSON[i];
            data.push(item.price_state);
            label.push(item.date_state);
        }
		if(document.querySelector('#Dabox .details_product .stats canvas'))
			document.querySelector('#Dabox .details_product .stats').removeChild(document.querySelector('#Dabox .details_product .stats canvas'));
		var canvas = document.createElement('canvas');
		canvas.setAttribute("width", w);
		canvas.setAttribute('height', h);
		document.querySelector('#Dabox .details_product .stats').appendChild(canvas);
		var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
		var lineChartData = {
			labels : label.reverse(),
			datasets : [
				{
					label: "",
					fillColor : "rgba(220,220,220,0.2)",
					strokeColor : "rgba(220,220,220,1)",
					pointColor : "rgba(220,220,220,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(220,220,220,1)",
					data : data.reverse()
				}
			]
		};
		var c = new Chart(canvas.getContext("2d")).Line(lineChartData, {responsive:false});

		toggleStatsHandler();
	}

	function extraContentHandler(e)
	{
		e.stopImmediatePropagation();
		e.stopPropagation();
		e.preventDefault();

		var id_link = document.querySelector('#Dabox .details_product').dataset.id_link;
		var t = e.currentTarget;
		switch(t.className)
		{
			case "icon-bars":
					Request.load('a/retrieve-states/?id_link='+id_link).onComplete(dataReadyForChartHandler);
				break;
			case "icon-percent":

				break;
			default:
				return;
		}
	}

	function daboxHandler()
	{
		if(document.querySelector('#Dabox .details_product .extra a'))
		{
			document.querySelectorAll('#Dabox .details_product .extra a').forEach(function(pItem){
				pItem.addEventListener('click', extraContentHandler, false);
			});
			document.querySelector('#Dabox .details_product .stats .back').addEventListener('click', toggleStatsHandler, false);
		}
	}

	function closeAllStickHandler(e)
	{
		if(e.target.nodeName.toLowerCase()=="input")
			return;
		document.removeEventListener('click', closeAllStickHandler, true);
		document.querySelectorAll('*[rel="stick"]').forEach(function(pItem){
			pItem.classList.add('hidden');
		});
	}

	function toggleStickHandler(e)
	{
		if(e)
		{
			e.preventDefault();
		}
		var t = e.currentTarget;
		document.querySelector(t.getAttribute('rel')).classList.toggle('hidden');
		document.addEventListener('click', closeAllStickHandler, true);
	}


	function init()
	{
		Dabox.onDisplay(daboxHandler);
		document.querySelectorAll('.toggle').forEach(function(pItem){
			pItem.addEventListener('click', toggleStickHandler, false);
			document.querySelector(pItem.getAttribute('rel')).setAttribute('rel', 'stick');
		});
	}

	NodeList.prototype.forEach = Array.prototype.forEach;
	window.addEventListener('load', init, false);
})();