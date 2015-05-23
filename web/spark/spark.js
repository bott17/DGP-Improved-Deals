function Spark (){
	
	console.log("ensemble...");
	this.audioComponent = new AudioComponent();
	
}



function AudioComponent(){
	
	
}

var create_email = false;
var final_transcript = '';
var recognizing = false;
var ignore_onend;
var start_timestamp;
if (!('webkitSpeechRecognition' in window)) {
  upgrade();
} else {
  var recognition = new webkitSpeechRecognition();
  recognition.continuous = true;
  recognition.interimResults = true;

  recognition.onstart = function() {
    recognizing = true;
    start_img.src = 'images/imgspark/mic-animate.gif';
  };

  recognition.onerror = function(event) {
    if (event.error == 'no-speech') {
      start_img.src = 'images/imgspark/mic.gif';
      ignore_onend = true;
    }
    if (event.error == 'audio-capture') {
      start_img.src = 'images/imgspark/mic.gif';
      ignore_onend = true;
    }
    if (event.error == 'not-allowed') {
      if (event.timeStamp - start_timestamp < 100) {
        showInfo('info_blocked');
      } else {
        showInfo('info_denied');
      }
      ignore_onend = true;
    }
  };

  recognition.onend = function() {
  	/*
    recognizing = false;
    if (ignore_onend) {
      return;
    }
    start_img.src = 'images/imgspark/mic.gif';
    if (!final_transcript) {
      //showInfo('images/imgspark/info_start');
      return;
    }
    if (window.getSelection) {çconsole.log("cuatro");
      window.getSelection().removeAllRanges();
      var range = document.createRange();
      range.selectNode(document.getElementById('titulo'));
      window.getSelection().addRange(range);
    }
    */
   	recognizing = false;
    checkFinAsistente();
  };

  recognition.onresult = function(event) {
   var interim_transcript = '';
    for (var i = event.resultIndex; i < event.results.length; ++i) {
      if (event.results[i].isFinal) {
        final_transcript = event.results[i][0].transcript;
      } else {
        interim_transcript = event.results[i][0].transcript;
      }
    }
   final_transcript = capitalize(final_transcript);
   console.log(final_transcript);
   console.log(interim_transcript);
    
    resultadosTemporalesSpark(interim_transcript);
    resultadosSpark(final_transcript);
    final_transcript="";
    interim_transcript="";
    
  };
}

var two_line = /\n\n/g;
var one_line = /\n/g;
function linebreak(s) {
  return s.replace(two_line, '<p></p>').replace(one_line, '<br>');
}

Spark.prototype.escuchar = function(event) {
	console.log("escuchando");

  if (recognizing) {
    recognition.stop();
    return;
  }
  final_transcript = '';
  recognition.lang = 'es';
  recognition.start();
  ignore_onend = false;
  start_img.src = 'images/imgspark/mic-slash.gif';
  start_timestamp = event.timeStamp;
  
};


Spark.prototype.sayHello = function (){
	console.log("hello world :D");
};


var first_char = /\S/;
function capitalize(s) {
  return s.replace(first_char, function(m) { return m.toUpperCase(); });
}
