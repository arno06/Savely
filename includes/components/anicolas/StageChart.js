var StageChart = (function(){

	var publicAPI = {};

	function init()
	{
		document.querySelectorAll('*[data-role="StageChart"]').forEach(publicAPI.create);
	}

	function SimpleChart(pElement)
	{
		if(!pElement)
			return;
		var outTimeOut = null;
		var tooltip = document.createElement('div');
		tooltip.classList.add('tooltip');
		tooltip.innerHTML = "test";
		pElement.appendChild(tooltip);
		var d = pElement.dataset;
		pElement.style.cssText = 'width:'+ d.width + 'px;height:'+ d.height+'px;border:dashed 1px #000;border-right:none;border-top:none;position:relative;';

		this.stage = new Stage(d.width, d.height, pElement);

		this.points = [];

		var overHandler = function(e){
			if(outTimeOut)
			{
				window.clearTimeout(outTimeOut);
				outTimeOut = null;
			}
			tooltip.innerHTML = e.currentTarget.dataset.value;
			var toLeft = (e.currentTarget.dataset.x - (tooltip.offsetWidth>>1))+"px";
			var toTop = (e.currentTarget.dataset.y - (tooltip.offsetHeight + 10))+"px";
			if(tooltip.style.opacity == 0)
			{
				tooltip.style.left = toLeft;
				tooltip.style.top = toTop;
			}
			M4Tween.killTweensOf(tooltip);
			M4Tween.to(tooltip,.3, {left:toLeft, top:toTop, opacity:1});
		};

		var outHandler = function(e){
			outTimeOut = window.setTimeout(function()
			{
				M4Tween.killTweensOf(tooltip);
				M4Tween.to(tooltip,.3, {opacity:0});
				outTimeOut = null;
			},.1);
		};

		this.data = eval(d.inputs)[0];

		var step = Math.round(d.width / (this.data.points.length + 1));
		var p, s, s2, xLabel, t;
		this.stage.beginFill('rgba(0, 0, 0, 0)');
		this.stage.setLineStyle(3, this.data.color);
		d.ymin = 999999;
		d.ymax = 0;
		for(var i = 0, max = this.data.points.length; i<max;i++)
		{
			d.ymin = Math.min(d.ymin, Number(this.data.points[i].value) - 100);
			d.ymax = Math.max(d.ymax, Number(this.data.points[i].value) + 100);
		}
		d.ymin = Math.max(0, d.ymin);
		var diffY = d.ymax - d.ymin;
		for(i = 0, max = this.data.points.length; i<max;i++)
		{
			p = this.data.points[i];
			p.value = Math.max(0, p.value);
			t = ((p.value - d.ymin) / diffY) * d.height;

			p.position = new Vector(((i+1) * step), d.height - t);

			s = document.createElement('span');
			s.addEventListener('mouseover', overHandler, false);
			s.addEventListener('mouseout', outHandler ,false);
			s.dataset.x = p.position.x;
			s.dataset.y = p.position.y;
			s.dataset.value = this.data.pointLabel.replace('{$value}', p.value);
			s.style.cssText = 'position:absolute;display:block;width:10px;height:10px;border-radius:50%;border:solid 2px #fff;background:'+this.data.color+';left:'+ (p.position.x-7)+'px;top:'+ (p.position.y-7)+'px;cursor:pointer;';
			pElement.appendChild(s);
			s2 = document.createElement('span');
			s2.style.cssText = 'pointer-events:none;display:block;width:6px;height:6px;background:#fff;border-radius:50%;margin:2px;';
			s.appendChild(s2);

			xLabel = document.createElement('div');
			xLabel.innerHTML = p.xLabel;
			xLabel.style.cssText = 'pointer-events:none;font-size:12px;position:absolute;color:#999;top:'+ (Number(d.height)+10)+'px;left:'+ p.position.x+'px';
			pElement.appendChild(xLabel);
			xLabel.style.marginLeft = (-(xLabel.offsetWidth>>1))+'px';

			if(i == 0)
			{
				this.stage.moveTo(p.position.x, p.position.y);
			}
			else
			{
				this.stage.lineTo(p.position.x, p.position.y);
			}

			this.points.push({stagePoint:p, domPoint:s, label:xLabel});
		}
	}

	publicAPI.create = function(pElement)
	{
		return new SimpleChart(pElement);
	};

	NodeList.prototype.forEach = Array.prototype.forEach;

	window.addEventListener('load', init, false);

	return publicAPI;
})();