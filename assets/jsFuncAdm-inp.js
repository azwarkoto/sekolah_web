/* 
File : jsfunction.js
Author : Danny Bastian M.
Use : Every Javascript function will be placed here for Sekolah-website project.
Version : 1.0.0
*/

$(document).ready(function(){

	//Automation Date
	function fillDate(){
	  var today = new Date();
	  if(today.getDate() < 10){
	    var date = '-0'+today.getDate();
	  }else{
	    var date = '-'+today.getDate();
	  }
	  if((today.getMonth()+1) < 10){
	    var month = '-0'+(today.getMonth()+1);
	  }else {
	    var month = '-'+(today.getMonth()+1);
	  }

	  var fullDate = today.getFullYear()+month+date;
	  $("input[type=date]").val(fullDate);
	  //document.getElementById("tgl1").value = fullDate;
	};
	fillDate();
	
	//MAPEL
	$(document).on("change",".mp-kelas",function(e){

		var th_v = $(this).val();
		var mpStts = "";

		if(th_v == "X"){
			mpStts = "MP-X";
		}else if(th_v == "XI"){
			mpStts = "MP-XI";
		}else if(th_v == "XII"){
			mpStts = "MP-XII";
		}

		$.ajax({
			type : 'POST',
			url : 'ajax-adm.php',
			data : {
				txInd : 'mapel-id',
				kelas : th_v,
			},
			complete : function(response){
				mpStts = mpStts + response.responseText;
				$(".mp-cd").val(mpStts);
			},
			erorr : function(){
				alert("Connection to database failed!");
			}
		});
	});

	$(document).on("click",".btn-add-mapel",function(e){

		$(".mp-err-msg").html("");
		var mp_cd = $(".mp-cd").val();
		var mp_nm = $(".mp-name").val();
		var kelas = $(".mp-kelas").val();

		if(!mp_nm){
			$(".mp-err-msg").html("Isi Nama Mata-Pelajaran!");
			return false;
		}else if(kelas == "0"){
			$(".mp-err-msg").html("Pilih kelas!");
			return false;
		}

		$.ajax({
			type : 'POST',
			url : 'ajax-adm.php',
			data : {
				txInd : 'mapel-insert',
				mpCd : mp_cd,
				mpNm : mp_nm,
				kelas : kelas,
			},
			complete : function(response){
				$(".mp-err-msg").html(mp_nm + " telah di input");
				$(".mp-kelas").trigger("change");
				$(".mp-name").val("");
				onLoadMpGuru();
			},
			erorr : function(){
				alert("Connection to database failed!");
			}
		});
	});
	// END OF MAPEL

	//GURU
	function onLoadMpGuru(e){
		$.ajax({
			type : 'POST',
			url : 'ajax-adm.php',
			data : {
				txInd : 'mapel-for-guru',
			},
			complete : function(response){
				$(".option-guru-mp").html(response.responseText);
			},
			erorr : function(){
				alert("Connection to database failed!");
			}
		});
	}

	onLoadMpGuru();

	$(document).on("click",".btn-add-guru",function(e){

		$(".guru-err-msg").html("");

		var guruMpCd = $(".guru-mp-cd").val();
		//alert(guruMpCd);

		$(".form-input-guru").find("input").each(function(){
			
			var pd = $(this).attr("placeholder");
			var pdFinal = pd + ' Tidak boleh kosong!';
		
			if($(this).prop('readonly')){
				$(".guru-err-msg").html("Masukkan data dengan benar!");
				return false;
			}else if($(this).val() == ''){
				$(".guru-err-msg").html(pdFinal);
				return false;				
			}
		});


		$(".form-input-guru").find("select").each(function(){
			
			var pd = $(this).attr("placeholder");
			var pdFinal = 'Pilih ' + pd;
		
			 if($(this).val() == '0'){
				$(".guru-err-msg").html(pdFinal);
				return false;				
			}
		});

		var guruCd = $(".guru-cd").val();
		var guruNm = $(".guru-nm").val();
		var guruNIP = $(".guru-nip").val();
		var guruJnsKlm = $(".guru-jns-klm").val();
		var guruTptLhr = $(".guru-tpt-lhr").val();
		var guruTglLhr = $(".guru-tgl-lhr").val();
		var guruAlmt = $(".guru-alamat").val();
		var guruTlp = $(".guru-tlp").val();
		var guruPddk = $(".guru-pddk").val();
		var guruJbtn = $(".guru-jbtn").val();
		var guruPass = $(".guru-pass").val();
		var guruMp = $(".guru-mp-cd").val();


		/*$.ajax({
			type : 'POST',
			url : 'ajax-adm.php',
			data : {
				txInd : 'mapel-insert',
				mpCd : mp_cd,
				mpNm : mp_nm,
				kelas : kelas,
			},
			complete : function(response){
				$(".mp-err-msg").html(mp_nm + " telah di input");
				$(".mp-kelas").trigger("change");
				$(".mp-name").val("");
				onLoadMpGuru();
			},
			erorr : function(){
				alert("Connection to database failed!");
			}
		});*/
		
	});


//END OF FILE
});