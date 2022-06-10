$(document).ready(function() {
	$("#test").hide();
	$("#createTest").click(function() {
		var amountOfQuestions = $("#questionAmount").val();
		var mcq = $("#mcq").is(":checked");
		var wq = $("#wq").is(":checked");
		var term = $("#term").is(":checked");
		var definition = $("#definition").is(":checked");
		if (amountOfQuestions > game.terms.length) amountOfQuestions = game.terms.length;
		if (amountOfQuestions < 1) amountOfQuestions = 1;
		if (mcq == false && wq == false) {
			confirm("Please choose at least one type of question.");
			return;
		}
		if (term == false && definition == false) {
			confirm("Please choose either term or definition to answer with.");
			return;
		}
		$("#testOptions").hide();
		$("#test").show();
		var test = new Test(mcq, wq, term, definition, amountOfQuestions);
		for (var i = 0; i < test.questions.length; i++) {
			var question = test.questions[i];
			var string = "<li answer='"+question.answer+"'><p>"+question.question+"</p>";
			if (question.mcq == true) {
				for (var i2 = 0; i2 < question.answers.length; i2++) {
					var answer = question.answers[i2];
					string += '<div class="form-check"><input class="form-check-input" name="question'+(i+1)+'" type="radio" id="question'+(i+1)+'option'+(i2+1)+'" value="'+question.answers[i2]+'"><label class="form-check-label" for="question'+(i+1)+'option'+(i2+1)+'">'+question.answers[i2]+'</label></div><br>';
				};
			}
			else {
				string += '<input type="text" class="form-control" id="question'+(i+1)+'" placeholder="Answer here."><br>';
			}
			$("#testQuestions").append(string+"</li>");
		};
	});
	$("#gradeTest").click(function() {
		$("#testQuestions").find("li").each(function() {
			$(this).find("span").remove();
			var answer = $(this).attr("answer");
			var correct = false;
			if ($(this).find("input").length == 4) {
				$(this).find("input").each(function() {
					if ($(this).is(":checked") && $(this).val() == answer) correct = true;
				});
			}
			else {
				if ($(this).find("input").first().val() == answer) correct = true;
			}
			if (correct == true) {
				$(this).find("p").first().append('<span style="color: green;"> - Correct</span>');
			}
			else {
				$(this).find("p").first().append('<span style="color: red;"> - Incorrect</span>');
			}
		});
	});
});

var Test = function(mcq, wq, term, def, q) {
	this.questions = [];
	this.mcq = mcq;
	this.wq = wq;
	this.term = term;
	this.def = def;
	this.quantity = q;
	for (var i = 0; i < this.quantity; i++) {
		var term = game.terms[i];
		var question = new Question(i);
		if (this.term == true && this.def == false) question.term = true;
		else if (this.term == false && this.def == true) question.term = false;
		else {
			if (randomNumber(1, 2) == 1) question.term = true;
			else question.term = false;
		}

		if (this.mcq == true && this.wq == false) question.mcq = true;
		else if (this.mcq == false && this.wq == true) question.mcq = false;
		else {
			if (randomNumber(1, 2) == 1) question.mcq = true;
			else question.mcq = false;
		}

		this.questions.push(question.generate());
	};
};

var Question = function(q, mcq, term) {
	this.q = q;
	this.mcq = mcq;
	this.term = term;
};

Question.prototype.generate = function() {
	var question = {};
	if (this.mcq == true) {
		question.mcq = true;
		question.answers = [];
		if (this.term == true) {
			question.question = game.terms[this.q].definition;
			var taken = [this.q];
			question.answer = game.terms[this.q].term;
			var done = false;
			for (var i = 0; i < 3; i++) {
				var ran = randomNumber(0, game.terms.length - 1);
				while (taken.includes(ran)) {
					ran = randomNumber(0, game.terms.length - 1);
				}
				taken.push(ran);
				question.answers.push(game.terms[ran].term);
				if (randomNumber(1, 3) == 1 && done == false) {
					question.answers.push(game.terms[this.q].term);
					done = true;
				}
			}
			if (done == false) question.answers.push(game.terms[this.q].term);
		}
		else {
			question.question = game.terms[this.q].term;
			var taken = [this.q];
			question.answers.push(game.terms[this.q].definition);
			question.answer = game.terms[this.q].definition;
			for (var i = 0; i < 3; i++) {
				var ran = randomNumber(0, game.terms.length - 1);
				while (taken.includes(ran)) {
					ran = randomNumber(0, game.terms.length - 1);
				}
				taken.push(ran);
				question.answers.push(game.terms[ran].definition);
			}
			question.answers = shuffle(question.answers);
		}
	}
	else {
		question.wq = true;
		if (this.term == true) {
			question.question = game.terms[this.q].definition;
			question.answer = game.terms[this.q].term;
		}
		else {
			question.question = game.terms[this.q].term;
			question.answer = game.terms[this.q].definition;
		}
	}
	return question;
}

function randomNumber(min, max) {
	return Math.floor(Math.random() * (max - min + 1)) + min;
}

function shuffle(array) {
	var currentIndex = array.length, temporaryValue, randomIndex;
	while (0 !== currentIndex) {
		randomIndex = Math.floor(Math.random() * currentIndex);
		currentIndex -= 1;
		temporaryValue = array[currentIndex];
		array[currentIndex] = array[randomIndex];
		array[randomIndex] = temporaryValue;
	}
	return array;
}