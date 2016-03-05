<?php if (is_null($english_category)) { ?>
	<h1>指定された問題はありません。</h1>
<?php } else { ?>
	<h1><?php echo $english_category->name; ?></h1>
	<div id="question"></div>


	<div class="jumbotron">
		<h3 class ="top left" id="title"></h3>

    	<table class="underMargin">
    		<tr>
    			<td class="left"><button class="btn btn-default btn-sm questionBtn" id="backBtn">&lt;&lt;back</button></td>
    			<td class="center"><button class="btn btn-default btn-sm questionBtn" id="giveupBtn">Give up</button></td>
    			<td class="center"><button class="btn btn-default btn-sm questionBtn" id="retryBtn">Retry</button></td>
    			<td class="right"><button class="btn btn-default btn-sm questionBtn" id="nextBtn">Next&gt;&gt;</button></td>
    		</tr>
    	</table>

		<?php foreach ($questions as $index => $row) { ?>
			<div class="audio audio<?php echo $index ?> hiden">
				<audio preload="metadata" id="audio<?php echo $index ?>" controls>
					<source src="<?php echo $row->voice_path ?>" type="audio/mp3">
				</audio>
			</div>
		<?php } ?>

    	<p class="lead"><span id="spell"></span></p>
    	<div class="result">
	    	<div class="left">日本語：<span id="japanese"></span></div>
	    	<!-- div class="left">解説：<span id="description"></span></div -->
    	</div>
	</div>

	<h4>Shortcut key</h4>
	<p>
		&lt : back<br>
		&gt : next<br>
		? : hint<br>
		+ : replay<br>
		* : giveup and retry
	</p>


	<script>
		$(document).ready(function() {
			var questions = $.parseJSON('<?php echo json_encode($questions); ?>');
			var question;
			var correct = "";
			var asterlisk = "";
			var myIndex = 0;
			var player;
			var notAlphaPressFlg = false;

			questionEnglish(0);

			window.document.onkeydown = keyClick;
			window.document.onkeyup = keyUp;

			$("#giveupBtn").on("click", function() {
				dispResult();
				cursorOut();
			});

			$("#retryBtn").on("click", function() {
				questionChange(0);
			});

			$("#backBtn").on("click", function() {
				questionChange(-1);
			});
			
			$("#nextBtn").on("click", function() {
				questionChange(1);
			});

			function questionChange(diff) {
				var index = myIndex + diff;
				if (index == -1 || questions.length - 1 < index) {
					return;
				}
				player.pause();
				questionEnglish(myIndex + diff);
				cursorOut();
			}

			function cursorOut() {
				$(".btn").blur();
			}

			function questionEnglish(index) {
				myIndex = index;
				$(".result").hide(); // 解説を隠す
				$("#giveupBtn").show(); // ギブアップボタンを表示する
				$("#title").html("Step" + (index + 1)); // ステップ数を表示する

				question = questions[index]; // 問題のオブジェクトを取得する
				correct = question.spell.replace(/[^a-z^A-Z]/g, ""); // アルファベットのみ抽出
				asterlisk = question.spell.replace(/[a-zA-Z]/g, "*"); // アルファベットを＊に置換する
				$("#spell").html(asterlisk); // ＊を表示する。

				dispAndPlayEnglish(index);

				$(".questionBtn").hide();
				if (questions.length > 0) {
					$("#giveupBtn").show(); // ギブアップボタンを表示する
					$("#retryBtn").show(); // リトライボタンを表示する
					if (index != 0) {
						$("#backBtn").show(); // 最初の問題以外は前へボタンを表示する
					}
					if (questions.length - 1 > index) {
						$("#nextBtn").show(); // 最後の問題以外は次へボタンを表示する
					}
				}
			}

			function dispAndPlayEnglish(index) {
				$(".audio").hide(); // audioコントローラを隠す
				$(".audio" + index).show(); // 選択された問題のコントローラだけ表示する
				player = document.getElementById("audio" + index); // コントローラを選択する
				player.currentTime = 0; // 音声の先頭に
				player.play(); // 再生
			}

			function keyClick(evt) {
				var inputChr = String.fromCharCode(evt.keyCode);
				var correctChr = correct.substring(0,1);
		
				var alpha = /[^a-z^A-Z]/g;
				alpha.exec(inputChr);
				if (alpha.lastIndex > 0) {
					console.log(inputChr);
					notAlphaPressFlg = true
					if ("¿" == inputChr) {
						// ?を打つと
						dispChrCorrect();
						hintSound();
					} else if ("¼" == inputChr) {
						// <を打つと
						questionChange(-1);
					} else if ("¾" == inputChr) {
						// >を打つと
						questionChange(1);
					} else if ("»" == inputChr) {
						dispAndPlayEnglish(myIndex);
					} else if ("Þ" == inputChr) {
						if ("" == correct) {
							questionEnglish(myIndex);
						} else {
							dispResult();
						}
					}
				} else if(!notAlphaPressFlg && "" != correct) {
					// 正解判定
					if (inputChr.toUpperCase() == correctChr.toUpperCase()) {
						dispChrCorrect();
						successSound();
					} else {
						failSound();
					}
				}

				if ("" == correct) {
					dispResult();
				}
			}

			function dispChrCorrect() {
				var correctChr = correct.substring(0,1);
				asterlisk = asterlisk.replace(/\*/, correctChr);
				$("#spell").html(asterlisk);
				correct = correct.substring(1, correct.length);
			}

			function keyUp(evt) {
				var inputChr = String.fromCharCode(evt.keyCode);
		
				var alpha = /[^a-z^A-Z]/g;
				alpha.exec(inputChr);
				if (alpha.lastIndex > 0) {
					notAlphaPressFlg = false;
				}
			}

			function dispResult() {
				$("#spell").html(question.spell);
				$("#japanese").html(question.japanese);
				//$("#description").html(question.description);
				$(".result").show();
				$("#giveupBtn").hide();
				correct = "";
			}

			function successSound() {
				sound("<?php echo base_url() ?>static_contents/sound/type_success.mp3");
			}

			function failSound() {
				sound("<?php echo base_url() ?>static_contents/sound/type_fail.mp3");
			}

			function hintSound() {
				sound("<?php echo base_url() ?>static_contents/sound/type_hint.mp3");
			}

			function sound(src) {
				var sound = new Audio(src);
				sound.play();
			}
		});
	</script>
<?php } ?>



