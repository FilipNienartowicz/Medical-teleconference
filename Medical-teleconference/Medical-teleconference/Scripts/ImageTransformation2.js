var Marker = function () {
	this.Sprite = new Image();
	this.Sprite.src = "http://www.iconsdb.com/icons/preview/green/map-marker-2-xxl.png"
	this.Width = 12;
	this.Height = 20;
	this.XPos = 0;
	this.YPos = 0;
}

function draw(color, mix, scale, translatePos, rotate, Markers, url, colorsRGB) {
	var canvas = document.getElementById("imageToTransform");
	var context = canvas.getContext("2d");
	var img = new Image();

	img.onload = function () {
		context.clearRect(0, 0, canvas.width, canvas.height);
		context.save();
		context.translate(translatePos.x, translatePos.y);
		context.rotate(rotate * Math.PI / 180);
		context.scale(scale, scale);
		context.drawImage(img, 0, 0);
		sharpen(mix);
		colors(color, colorsRGB);

		for (var i = 0; i < Markers.length; i++) {
			var tempMarker = Markers[i];
			console.log(tempMarker);
			//context.drawImage(tempMarker.Sprite, tempMarker.XPos, tempMarker.YPos, tempMarker.Width, tempMarker.Height);
		}
		context.restore();

		/*for (var i = 0; i < Markers.length; i++) {
		  var tempMarker = Markers[i];
		  context.drawImage(tempMarker.Sprite, tempMarker.XPos, tempMarker.YPos, tempMarker.Width, tempMarker.Height);
		}
		context.restore();*/
	}
	img.src = $("#urlWhy").text();

}

function colors(color, colorsRGB) {
	var canvas = document.getElementById("imageToTransform");
	var ctx = canvas.getContext("2d");
	var w = canvas.width;
	var h = canvas.height;
	var dstData = ctx.getImageData(0, 0, w, h);

	for (var i = 0; i < dstData.data.length; i += 4) {
		var tmpr = dstData.data[i + 0] + colorsRGB.r;
		dstData.data[i + 0] = tmpr | dstData.data[i + 0];
		var tmpg = dstData.data[i + 1] + colorsRGB.g;
		dstData.data[i + 1] = tmpg | dstData.data[i + 1];
		var tmpb = dstData.data[i + 2] + colorsRGB.b;
		dstData.data[i + 2] = tmpb | dstData.data[i + 2];
	}

	ctx.putImageData(dstData, 0, 0);
}

function sharpen(mix) {
	var canvas = document.getElementById("imageToTransform");
	var ctx = canvas.getContext("2d");
	var w = canvas.width;
	var h = canvas.height;

	var weights = [0, -1, 0, -1, 5, -1, 0, -1, 0],
		katet = Math.round(Math.sqrt(weights.length)),
		half = (katet * 0.5) | 0,
		dstData = ctx.createImageData(w, h),
		dstBuff = dstData.data,
		srcBuff = ctx.getImageData(0, 0, w, h).data,
		y = h;

	while (y--) {
		x = w;
		while (x--) {
			var sy = y,
				sx = x,
				dstOff = (y * w + x) * 4,
				r = 0,
				g = 0,
				b = 0,
				a = 0;

			for (var cy = 0; cy < katet; cy++) {
				for (var cx = 0; cx < katet; cx++) {
					var scy = sy + cy - half;
					var scx = sx + cx - half;

					if (scy >= 0 && scy < h && scx >= 0 && scx < w) {
						var srcOff = (scy * w + scx) * 4;
						var wt = weights[cy * katet + cx];

						r += srcBuff[srcOff] * wt;
						g += srcBuff[srcOff + 1] * wt;
						b += srcBuff[srcOff + 2] * wt;
						a += srcBuff[srcOff + 3] * wt;
					}
				}
			}

			dstBuff[dstOff] = r * mix + srcBuff[dstOff] * (1 - mix);
			dstBuff[dstOff + 1] = g * mix + srcBuff[dstOff + 1] * (1 - mix);
			dstBuff[dstOff + 2] = b * mix + srcBuff[dstOff + 2] * (1 - mix)
			dstBuff[dstOff + 3] = srcBuff[dstOff + 3];
		}
	}

	ctx.putImageData(dstData, 0, 0);
}

window.onload = function () {
	var canvas = document.getElementById("imageToTransform");
	var url = canvas.toDataURL();

	var Markers = new Array();

	var colorsRGB = {
		r: 0,
		g: 0,
		b: 0
	};

	var translatePos = {
		x: 0,
		y: 0
	};

	var scale = 1.0;
	var rotate = 0;
	var degree = 15;
	var mix = 0;
	var sharpen = 20;
	var scaleMultiplier = 0.8;
	var startDragOffset = {};
	var mouseDown = false;

	// add button event listeners
	document.getElementById("plus").addEventListener("click", function () {
		scale /= scaleMultiplier;
		draw(3, mix * 0.01, scale, translatePos, rotate, Markers, url, colorsRGB);
	}, false);

	document.getElementById("minus").addEventListener("click", function () {
		scale *= scaleMultiplier;
		draw(3, mix * 0.01, scale, translatePos, rotate, Markers, url, colorsRGB);
	}, false);

	document.getElementById("rotateRight").addEventListener("click", function () {
		rotate += degree;
		draw(3, mix * 0.01, scale, translatePos, rotate, Markers, url, colorsRGB);
	}, false);

	document.getElementById("rotateLeft").addEventListener("click", function () {
		rotate -= degree;
		draw(3, mix * 0.01, scale, translatePos, rotate, Markers, url, colorsRGB);
	}, false);

	document.getElementById("sharp").addEventListener("click", function () {
		mix += sharpen;
		draw(3, mix * 0.01, scale, translatePos, rotate, Markers, url, colorsRGB);
	}, false);

	document.getElementById("unsharp").addEventListener("click", function () {
		mix -= sharpen;
		draw(3, mix * 0.01, scale, translatePos, rotate, Markers, url, colorsRGB);
	}, false);

	document.getElementById("redPlus").addEventListener("click", function () {
		colorsRGB.r += 1;
		draw(0, mix * 0.01, scale, translatePos, rotate, Markers, url, colorsRGB);
	}, false);

	document.getElementById("greenPlus").addEventListener("click", function () {
		colorsRGB.g += 1;
		draw(1, mix * 0.01, scale, translatePos, rotate, Markers, url, colorsRGB);
	}, false);

	document.getElementById("bluePlus").addEventListener("click", function () {
		colorsRGB.b += 1;
		draw(2, mix * 0.01, scale, translatePos, rotate, Markers, url, colorsRGB);
	}, false);

	document.getElementById("redMinus").addEventListener("click", function () {
		colorsRGB.r -= 1;
		draw(0, mix * 0.01, scale, translatePos, rotate, Markers, url, colorsRGB);
	}, false);

	document.getElementById("greenMinus").addEventListener("click", function () {
		colorsRGB.g -= 1;
		draw(1, mix * 0.01, scale, translatePos, rotate, Markers, url, colorsRGB);
	}, false);

	document.getElementById("blueMinus").addEventListener("click", function () {
		colorsRGB.b -= 1;
		draw(2, mix * 0.01, scale, translatePos, rotate, Markers, url, colorsRGB);
	}, false);

	document.getElementById('imageFile').addEventListener("click", function () {
		colorsRGB = {
			r: 0,
			g: 0,
			b: 0
		};
		translatePos = {
			x: 0,
			y: 0
		};
		scale = 1.0;
		rotate = 0;
		degree = 15;
		mix = 20;
		sharpen = 10;
		scaleMultiplier = 0.8;
		startDragOffset = {};
		mouseDown = false;

	}, false);

	// add event listeners to handle screen drag
	canvas.addEventListener("mousedown", function (evt) {
		if (evt.which == 1) {
			mouseDown = true;
			startDragOffset.x = evt.clientX - translatePos.x;
			startDragOffset.y = evt.clientY - translatePos.y;
		}
		else if (evt.which == 2) {
			var rect = canvas.getBoundingClientRect();
			var mouseXPos = (evt.clientX - rect.left);
			var mouseYPos = (evt.clientY - rect.top);
			var marker = new Marker();
			marker.XPos = mouseXPos - (marker.Width / 2);
			marker.YPos = mouseYPos - marker.Height;
			Markers.push(marker);
			console.log(marker);
			//draw(mix * 0.01, scale, translatePos, rotate, Markers, url);
		}
	});

	canvas.addEventListener("mouseup", function (evt) {
		mouseDown = false;
	});

	canvas.addEventListener("mouseover", function (evt) {
		mouseDown = false;
	});

	canvas.addEventListener("mouseout", function (evt) {
		mouseDown = false;
	});

	canvas.addEventListener("mousemove", function (evt) {
		if (mouseDown) {
			translatePos.x = evt.clientX - startDragOffset.x;
			translatePos.y = evt.clientY - startDragOffset.y;
			draw(3, mix * 0.01, scale, translatePos, rotate, Markers, url, colorsRGB);
		}
	});

	draw(3, mix * 0.01, scale, translatePos, rotate, Markers, url, colorsRGB);
};
