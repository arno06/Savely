var main  = (function(){

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
		e&& e.preventDefault();
		var t = e.currentTarget;
		document.querySelector(t.getAttribute('rel')).classList.toggle('hidden');
		document.addEventListener('click', closeAllStickHandler, true);
	}

	function openLinkContextHandler(e)
	{
		if (e&& (e.target.nodeName.toLowerCase() != "a" || e.target.getAttribute("href") == ""))
		{
			e.preventDefault();
			e.stopImmediatePropagation();
			e.stopPropagation();
		}
		else
		{
			return;
		}

		Product.display(e.currentTarget.dataset.id, e.currentTarget.getAttribute("rel"));
	}


	function init()
	{
		document.querySelectorAll('.toggle').forEach(function(pItem){
			pItem.addEventListener('click', toggleStickHandler, false);
			document.querySelector(pItem.getAttribute('rel')).setAttribute('rel', 'stick');
		});

		document.querySelectorAll('*[rel^="shoplater:"]').forEach(function(pItem){
			pItem.setAttribute('rel', pItem.getAttribute('rel').split(':')[1]);
			pItem.addEventListener('click', openLinkContextHandler, false);
		});

		document.querySelector('.add a[rel="#addForm"]').addEventListener('click', function(e)
		{
			e&& e.preventDefault();
			var t = document.querySelector('#addForm');
			t.classList.toggle('hidden');
		});
	}

	window.addEventListener('load', init, false);
})();

var Product = (function(){
	var publicAPI = {};
	var overlay;
	var details;

	function tabHandler(e)
	{
		e.preventDefault();

		details.querySelectorAll('.tabs>.current, menu li a.current').forEach(function(pItem){
			pItem.classList.remove('current');
		});
		details.querySelector('.tabs>.'+ e.currentTarget.getAttribute('rel')).classList.add('current');
		e.currentTarget.classList.add('current');
	}

	function resultHandler(pReq)
	{
		var ref = this;
		overlay.innerHTML = pReq.responseText;
		details = document.getElementById('details_products');
		StageChart.create(document.getElementById('dataGraph'));
		setTimeout(function(){
			details.classList.remove('hidden');
		}, 500);

		details.querySelector('.close a').addEventListener('click', publicAPI.hide, false);

		details.querySelectorAll('a[rel^="tab:"').forEach(function(pItem)
		{
			pItem.setAttribute("rel", pItem.getAttribute("rel").replace("tab:", ""));
			pItem.addEventListener('click', tabHandler, false);
		});

		details.querySelectorAll('li.right a[class^="icon-"').forEach(function(pItem)
		{
			pItem.addEventListener('click', function(e)
			{
				e.preventDefault();
				var href = e.currentTarget.getAttribute('href');

				if(href == "")
				{
					console.log("not implemented yet");
					return;
				}

				switch(e.currentTarget.className)
				{
					case "icon-remove":
						details.querySelector('.confirm-box').style.display = 'block';
						details.querySelector('.confirm-box a[rel="yes"]').onclick = function(){
							Request.load(href).onComplete(function(pRequest)
							{
								console.log(pRequest);
								publicAPI.hide();
								var el = document.querySelector('.content #link_'+publicAPI.id);
								el.parentNode.removeChild(el);
							});
							return false;
						};
						details.querySelector('.confirm-box a[rel="no"]').onclick = function(){details.querySelector('.confirm-box').style.display = 'none';return false;};
						break;
				}
			}, false);
		});
	}

	publicAPI.display = function(pId, pTab)
	{
		publicAPI.id = pId;
		overlay.classList.remove('hidden');

		Request.load('index/details/?id='+pId+'&tab='+pTab)
			.onComplete(resultHandler);
	};

	publicAPI.hide = function(e)
	{
		if(e && e.currentTarget !== e.target)
			return;
		details.classList.add('hidden');
		setTimeout(function(){
			overlay.classList.add('hidden');
		}, 500);
	};

	function init()
	{
		overlay = document.getElementById('overlay_products');
		overlay.addEventListener('click', publicAPI.hide, false);
	}

	window.addEventListener('load', init, false);
	return publicAPI;
})();

NodeList.prototype.forEach = Array.prototype.forEach;