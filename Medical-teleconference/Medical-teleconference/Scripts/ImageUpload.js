// jqXHRData needed for grabbing by data object of fileupload scope
var jqXHRData;

$(function () {

	//Initialization of fileupload
	initSimpleFileUpload();

	//Handler for "Start upload" button 
	$("#form0").on('submit', function () {
		console.log("Submit kot");
		if (jqXHRData) {
			jqXHRData.submit();
		}
		return false;
	});

	$("#imageList a").on('click', function () {
		console.log("clicked", $(this));
		getImage($(this).attr("data"))
			.done(function (res) {
				drawDownloadedImage(res);
			})
			.fail(function () {
				console.log(":'(");
			});
		return false;
	});
});

function initSimpleFileUpload() {
	'use strict';

	console.log($('#imageFile'));

	$('#imageFile').fileupload({
		url: '/Conference/Upload',
		dataType: 'json',
		add: function (e, data) {
			jqXHRData = data
		},
		done: function (event, data) {
			if (data.result.isUploaded) {

			}
			else {

			}
			alert(data.result.message);
		},
		fail: function (event, data) {
			if (data.files[0].error) {
				alert(data.files[0].error);
			}
		}
	});
}

function getImage(id) {
	var url = `/Conference/Show/${id}`;

	return $.get(url);
}

function drawDownloadedImage(imageSrc) {
	var canvas = document.getElementById("imageToTransform");
	var context = canvas.getContext("2d");
	var img = new Image();

	img.onload = function () {
		context.clearRect(0, 0, canvas.width, canvas.height);
		context.save();
		context.drawImage(img, 0, 0);
	}
	img.src = imageSrc;
	$("#urlWhy").text(imageSrc);
}