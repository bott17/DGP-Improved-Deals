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
  recognition.continuous = false;
  recognition.interimResults = false;

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
    	recognizing = false;
	   	if (ignore_onend) {
	      return;
	    }
	    start_img.src = 'images/imgspark/mic.gif';
	    if (!final_transcript) {
	      //showInfo('images/imgspark/info_start');
	      return;
	    }
	    if (window.getSelection) {
	      window.getSelection().removeAllRanges();
	      var range = document.createRange();
	      range.selectNode(document.getElementById('titulo'));
	      window.getSelection().addRange(range);
	    }
    // }
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
   //console.log(interim_transcript);
    
    //resultadosTemporalesSpark(interim_transcript);
    resultadosSpark(final_transcript);
    final_transcript="";
    //interim_transcript="";
    
  };
}

var two_line = /\n\n/g;
var one_line = /\n/g;
function linebreak(s) {
  return s.replace(two_line, '<p></p>').replace(one_line, '<br>');
}

Spark.prototype.escuchar = function() {
	console.log("escuchando");

  if (recognizing) {
  	console.log("parando");
    recognition.stop();
    return;
  }
  console.log("siguiendo");
  final_transcript = '';
  recognition.lang = 'es';
  recognition.start();
  ignore_onend = false;
  start_img.src = 'images/imgspark/mic-slash.gif';
  +new Date;
  start_timestamp = new Date().getTime();
};


Spark.prototype.sayHello = function (){
	console.log("hello world :D");
};


var first_char = /\S/;
function capitalize(s) {
  return s.replace(first_char, function(m) { return m.toUpperCase(); });
}


Spark.prototype.hablar = function(text, callback){
	// say a message
    var u = new SpeechSynthesisUtterance();
    u.text = text;
    u.lang = 'es-Es';
 
    u.onend = function () {
        if (callback) {
            callback();
        }
    };
 
    u.onerror = function (e) {
        if (callback) {
            callback(e);
        }
    };
 
    speechSynthesis.speak(u);
};


// ask a question and get an answer
Spark.prototype.pregunta = function (text, callback) {
    // ask question
    this.hablar(text, function () {
        // get answer
        var recognition = new webkitSpeechRecognition();
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = 'es';
        
        recognition.onstart = function() {
		    console.log("start");
		};
		 
        recognition.onend = function (e) {
            if (callback) {
                callback('no results');
            }
        };
 
        recognition.onresult = function (e) {
            // cancel onend handler
            console.log("hola 1" + final_transcript);
            recognition.onend = null;
            if (callback) {
            	console.log("holaaaa " + final_transcript + "asddas " + e.results[0][0].transcript);
                callback(null, {
                    final_transcript: e.results[0][0].transcript,
                    confidence: e.results[0][0].confidence
                });
                
                final_transcript = capitalize(final_transcript);
			   console.log("hola 3" + final_transcript);
			   //console.log(interim_transcript);
			    
			    //resultadosTemporalesSpark(interim_transcript);
			    resultadosSpark(final_transcript);
			    final_transcript="";
			    //interim_transcript="";
                
            }
        };
 
 console.log("temp");
        // start listening
        recognition.start();
    });
};
