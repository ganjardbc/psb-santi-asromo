jQuery(document).ready(function() {
	var _files = "";
	$("#loading_submit").hide();
	$.validator.messages.required = 'Harap di isi';
	$("#button_submit").click(function(){
		if (_files=="") {
	   		swal(
                'Error',
                'Foto harus di isi',
                'error'
            );
	   	}else{
	   		if ($("#myform").valid()) {
	   			$("#loading_submit").show();
				$("#button_submit").hide();
				var $filename = $("input[name=filename]");
	   			var fd = new FormData();
			    var files = _files;
			    fd.append('file',files);
			    var filename = moment().unix().toString()+"_"+$("#nama").val().replace(/\s/g, '_');
			    var ext = files.name.includes('.jpeg') ? files.name.slice(-4) : files.name.slice(-3);
			    $filename.val(filename+'.'+ext);
			    fd.append('name', filename);
			    $.ajax({
			        url: 'upload_foto',
			        type: 'post',
			        data: fd,
			        cache:false,
			        contentType: false,
			        processData: false,
			        error: function(xhr, status, error) {
			            if(status === 400) {
			                swal(
    			                'Error',
    			                'Photo silahkan isi dengan extensi jpg, png, jpeg',
    			                'error'
    			            );
			            }else{
			                swal(
    			                'Error',
    			                'Error please try again...',
    			                'error'
    			            );
			            }
					  	
			            $("#loading_submit").hide();
						$("#button_submit").show();
					},
			        success:function(resp){
			        	$('#myform').submit();
			        }
			    });
	   		};
	   	}
	});
	
	
	$("[uppercase]").on('keypress', function (e) {
        var $this = $(this);
        var placeholder = $this.attr('placeholder');
        if(placeholder && placeholder.length > 0) {
            this["_placeholder"] = placeholder;
            $this.attr('placeholder', '');
        }
        $this.css('text-transform', 'uppercase');
        $this.val($this.val().toUpperCase());
    }).on('keyup blur', function () {
        var $this = $(this);
        if ($this.val().length < 1) {
            $this.css("text-transform", "");
            $this.attr("placeholder", this["_placeholder"]);
            this["_placeholder"] = undefined;
        }
        $this.val($this.val().toUpperCase());
    });
    $('#asal_sekolah').append('<option disabled selected hidden value="">---Pilih asal sekolah---</option>');
	var jenjang = '';
	var pindahan_smp=[{text:"7",value:"7"},{text:"8",value:"8"}],
		pindahan_sma=[{text:"10",value:"10"},{text:"11",value:"11"}],
		asal_smp=[{text:"SD NEGERI",value:"SD NEGERI"},{text:"SD SWASTA",value:"SD SWASTA"},{text:"MI NEGERI",value:"MI NEGERI"},{text:"MI SWASTA",value:"MI SWASTA"},{text:"PERSAMAAN SD",value:"PERSAMAAN SD"}],
		asal_sma=[{text:"SMP NEGERI",value:"SMP NEGERI"},{text:"SMP SWASTA",value:"SMP SWASTA"},{text:"MTS NEGERI",value:"MTS NEGERI"},{text:"MTS SWASTA",value:"MTS SWASTA"},{text:"PERSAMAAN SLTP",value:"PERSAMAAN SLTP"}];
	var bulan = [{text:"Januari",value:"01"},{text:"Februari",value:"02"},{text:"Maret",value:"03"},{text:"April",value:"04"},{text:"Mei",value:"05"},{text:"Juni",value:"06"},{text:"Juli",value:"07"},{text:"Agustus",value:"08"},{text:"September",value:"09"},{text:"Oktober",value:"10"},{text:"November",value:"11"},{text:"Desember",value:"12"}]
	var pendidikan = [
         {'id': 2, 'label': 'SD'},
         {'id': 3, 'label': 'MTS'},
         {'id': 4, 'label': 'SMP'},
         {'id': 5, 'label': 'MA'},
         {'id': 6, 'label': 'SMA'},
         {'id': 7, 'label': 'D1'},
         {'id': 8, 'label': 'D2'},
         {'id': 9, 'label': 'D3'},
         {'id': 10, 'label': 'S1'},
         {'id': 11, 'label': 'S2'},
         {'id': 12, 'label': 'S3'},
         {'id': 13, 'label': 'PROF'}
    ];
	display_tahun();
	display_pendidikan();

	$("#bulan_lahir").change(function() {
	    $("#hari_lahir").prop("disabled",false);
	    display_hari($('#tahun_lahir').val()+"-"+$('#bulan_lahir').val())
	});
	$('#bulan_lahir').append('<option disabled selected hidden value="">Bulan</option>');
	$('#hari_lahir').append('<option disabled selected hidden value="">Tanggal</option>');

	$("#tahun_lahir").change(function() {
		if ($("#bulan_lahir").val() != null) {
			display_hari($('#tahun_lahir').val()+"-"+$('#bulan_lahir').val())
		}else{
			$("#bulan_lahir").prop("disabled",false);
	    	display_bulan();
		}
	    
	});

	function disable_wil(id,status){
		$(id).prop("disabled",status);
	}

	$('#provinsi,#kabupaten,#kecamatan,#kelurahan,#provinsi_ortu,#kabupaten_ortu,#kecamatan_ortu,#kelurahan_ortu').select2({
		theme: "bootstrap4"
	});
	$.getJSON("wil/provinsi", function(response) {
		$('#provinsi').empty();
		$('#provinsi').append('<option disabled selected hidden value="">---Pilih provinsi---</option>');
		$('#kabupaten').append('<option disabled selected hidden value="">---Pilih kabupaten---</option>');
		$('#kecamatan').append('<option disabled selected hidden value="">---Pilih kecamatan---</option>');
		$('#kelurahan').append('<option disabled selected hidden value="">---Pilih kelurahan---</option>');
		disable_wil("#kabupaten",true);
		disable_wil("#kecamatan",true);
		disable_wil("#kelurahan",true);
		$('#provinsi_ortu').empty();
		$('#provinsi_ortu').append('<option disabled selected hidden value="">---Pilih provinsi ortu---</option>');
		$('#kabupaten_ortu').append('<option disabled selected hidden value="">---Pilih kabupaten ortu---</option>');
		$('#kecamatan_ortu').append('<option disabled selected hidden value="">---Pilih kecamatan ortu---</option>');
		$('#kelurahan_ortu').append('<option disabled selected hidden value="">---Pilih kelurahan ortu---</option>');
		disable_wil("#kabupaten_ortu",true);
		disable_wil("#kecamatan_ortu",true);
		disable_wil("#kelurahan_ortu",true);
		for (var i = 0; i < response.length; i++) {
		 	var data = response[i];
		 	$('#provinsi').append('<option value="'+data.id_prov+'">'+data.nama+'</option>');
		 	$('#provinsi_ortu').append('<option value="'+data.id_prov+'">'+data.nama+'</option>');
		};
	});
	$("#provinsi").change(function() {
		$("input[name=provinsi]").val($("#provinsi option:selected").text());
		$.getJSON("wil/kabupaten/"+$('#provinsi').val(), function(response) {
			$('#kabupaten').empty();
			$('#kabupaten').append('<option disabled selected hidden value="">---Pilih kabupaten---</option>');
			$('#kecamatan').append('<option disabled selected hidden value="">---Pilih kecamatan---</option>');
			$('#kelurahan').append('<option disabled selected hidden value="">---Pilih kelurahan---</option>');
			disable_wil("#kabupaten",false);
			disable_wil("#kecamatan",true);
			disable_wil("#kelurahan",true);
			for (var i = 0; i < response.length; i++) {
			 	var data = response[i];
			 	$('#kabupaten').append('<option value="'+data.id_kab+'">'+data.nama+'</option>');
			};
		});
	});

	$("#kabupaten").change(function() {
		$("input[name=kabupaten]").val($("#kabupaten option:selected").text());
		$.getJSON("wil/kecamatan/"+$('#kabupaten').val(), function(response) {
			$('#kecamatan').empty();
			$('#kecamatan').append('<option disabled selected hidden value="">---Pilih kecamatan---</option>');
			$('#kelurahan').append('<option disabled selected hidden value="">---Pilih kelurahan---</option>');
			disable_wil("#kecamatan",false);
			disable_wil("#kelurahan",true);
			for (var i = 0; i < response.length; i++) {
			 	var data = response[i];
			 	$('#kecamatan').append('<option value="'+data.id_kec+'">'+data.nama+'</option>');
			};
		});
	});

	$("#kecamatan").change(function() {
		$("input[name=kecamatan]").val($("#kecamatan option:selected").text());
		$.getJSON("wil/kelurahan/"+$('#kecamatan').val(), function(response) {
			$('#kelurahan').empty();
			$('#kelurahan').append('<option disabled selected hidden value="">---Pilih kelurahan---</option>');
			disable_wil("#kelurahan",false);
			for (var i = 0; i < response.length; i++) {
			 	var data = response[i];
			 	$('#kelurahan').append('<option value="'+data.id_kel+'">'+data.nama+'</option>');
			};
		});
	});

	$("#kelurahan").change(function() {
		$("input[name=kelurahan]").val($("#kelurahan option:selected").text());
		
	});

	//ortu
	$("#provinsi_ortu").change(function() {
		$("input[name=provinsi_ortu]").val($("#provinsi_ortu option:selected").text());
		$.getJSON("wil/kabupaten/"+$('#provinsi_ortu').val(), function(response) {
			$('#kabupaten_ortu').empty();
			$('#kabupaten_ortu').append('<option disabled selected hidden value="">---Pilih kabupaten ortu---</option>');
			$('#kecamatan_ortu').append('<option disabled selected hidden value="">---Pilih kecamatan ortu---</option>');
			$('#kelurahan_ortu').append('<option disabled selected hidden value="">---Pilih kelurahan ortu---</option>');
			disable_wil("#kabupaten_ortu",false);
			disable_wil("#kecamatan_ortu",true);
			disable_wil("#kelurahan_ortu",true);
			for (var i = 0; i < response.length; i++) {
			 	var data = response[i];
			 	$('#kabupaten_ortu').append('<option value="'+data.id_kab+'">'+data.nama+'</option>');
			};
		});
	});

	$("#kabupaten_ortu").change(function() {
		$("input[name=kabupaten_ortu]").val($("#kabupaten_ortu option:selected").text());
		$.getJSON("wil/kecamatan/"+$('#kabupaten_ortu').val(), function(response) {
			$('#kecamatan_ortu').empty();
			$('#kecamatan_ortu').append('<option disabled selected hidden value="">---Pilih kecamatan ortu---</option>');
			$('#kelurahan_ortu').append('<option disabled selected hidden value="">---Pilih kelurahan ortu---</option>');
			disable_wil("#kecamatan_ortu",false);
			disable_wil("#kelurahan_ortu",true);
			for (var i = 0; i < response.length; i++) {
			 	var data = response[i];
			 	$('#kecamatan_ortu').append('<option value="'+data.id_kec+'">'+data.nama+'</option>');
			};
		});
	});

	$("#kecamatan_ortu").change(function() {
		$("input[name=kecamatan_ortu]").val($("#kecamatan_ortu option:selected").text());
		$.getJSON("wil/kelurahan/"+$('#kecamatan_ortu').val(), function(response) {
			$('#kelurahan_ortu').empty();
			$('#kelurahan_ortu').append('<option disabled selected hidden value="">---Pilih kelurahan ortu---</option>');
			disable_wil("#kelurahan_ortu",false);
			for (var i = 0; i < response.length; i++) {
			 	var data = response[i];
			 	$('#kelurahan_ortu').append('<option value="'+data.id_kel+'">'+data.nama+'</option>');
			};
		});
	});
	$("#kelurahan_ortu").change(function() {
		$("input[name=kelurahan_ortu]").val($("#kelurahan_ortu option:selected").text());
		
	});

	$("#bulan_lahir").prop("disabled",true);
	$("#hari_lahir").prop("disabled",true);
	
	function display_pendidikan(){
		$('#pendidikan_ibu').empty();
		$('#pendidikan_ayah').empty();
		$('#pendidikan_ibu').append('<option disabled selected hidden value="">---Pilih pendidikan ibu---</option>');
		$('#pendidikan_ayah').append('<option disabled selected hidden value="">---Pilih pendidikan ayah---</option>');
		for (var i = 0; i < pendidikan.length; i++) {
			var data = pendidikan[i];
			$('#pendidikan_ibu').append('<option value="'+data.label+'">'+data.label+'</option>'); 
			$('#pendidikan_ayah').append('<option value="'+data.label+'">'+data.label+'</option>'); 
		};
	}

	function display_tahun(){
		var year_now = moment().format('Y');
		var limit_year = 20;
		var year = [];
		for (var i = 0; i < limit_year; i++) {
			var start = parseInt(year_now) - limit_year;
			year.push((start+(i+1)).toString());
		};
		$('#tahun_lahir').empty();
		$('#tahun_lahir').append('<option disabled selected hidden value="">Tahun</option>');
		for (var i = 0; i < year.length; i++) {
			var data = year[i];
			$('#tahun_lahir').append('<option value="'+data+'">'+data+'</option>'); 
		};
	}

	

	function display_bulan(){
		var month = bulan;
		$('#bulan_lahir').empty();
		$('#bulan_lahir').append('<option disabled selected hidden value="">Bulan</option>');
		for (var i = 0; i < month.length; i++) {
			var data = month[i];
			$('#bulan_lahir').append('<option value="'+data.value+'">'+data.text+'</option>'); 
		};
	}

	function display_hari(tahun_bulan){
		var daysInMonth = moment(tahun_bulan,"YYYY-MM").daysInMonth();
		var arrDays = [];
		for (var i = 0; i < daysInMonth; i++) {
			var day = (i+1).toString();
			var nol = "0";
			day = day.length === 1 ? nol+day : day;
			arrDays.push(day);
		};
		$('#hari_lahir').empty();
		$('#hari_lahir').append('<option disabled selected hidden value="">Tanggal</option>');
		for (var i = 0; i < arrDays.length; i++) {
			var data = arrDays[i];
			$('#hari_lahir').append('<option value="'+data+'">'+data+'</option>'); 
		};
	}

	function display_pindahan_kelas(array){
		$('#pindahan_kelas').empty();
		$('#pindahan_kelas').append('<option disabled selected hidden value="">---Pilih pindahan kelas---</option>');
		for (var i = 0; i < array.length; i++) {
			var data = array[i];
			$('#pindahan_kelas').append('<option value="'+data.value+'">'+data.text+'</option>'); 
		};
	}

	function display_asal_sekolah(array){
		$('#asal_sekolah').empty();
		$('#asal_sekolah').append('<option disabled selected hidden value="">---Pilih asal sekolah---</option>');
		for (var i = 0; i < array.length; i++) {
			var data = array[i];
			$('#asal_sekolah').append('<option value="'+data.value+'">'+data.text+'</option>'); 
		};
	}

	$("#jenjang").change(function() {
	    var value = $(this).val();
	    jenjang = value;
	    if (jenjang === 'SMP') {
    		display_asal_sekolah(asal_smp);
    	}else{
    		display_asal_sekolah(asal_sma);
    	}
	    
	});

	$("#take_foto").click(function () {
		console.log('test');
		// $("#modal_take").modal("show");
		$("#modal_take").show();
		if(window.innerHeight < window.innerWidth){
			Webcam.set({
			  width: 320,
			  height: 240,
			  image_format: 'jpeg',
			  jpeg_quality: 90,
			  flip_horiz: true,
			});
		}else{
			Webcam.set({
			  width: 240,
			  height: 320,
			  image_format: 'jpeg',
			  jpeg_quality: 90,
			  flip_horiz: true,
			});
		}
		
		$("#snap_ok_div").hide();
		$("#snap_foto_div").show();
		Webcam.attach('#my_camera');
	});

	$("#close_take_modal").click(function () {
		$("#modal_take").hide();
		Webcam.reset();
		
	});
	function urltoFile(url, filename, mimeType){
	    mimeType = mimeType || (url.match(/^data:([^;]+);/)||'')[1];
	    return (fetch(url)
	        .then(function(res){return res.arrayBuffer();})
	        .then(function(buf){return new File([buf], filename, {type:mimeType});})
	    );
	}
	var sound = new Audio("assets/js/shutter.mp3");
	
	var data_uri_image = "";
	$("#snap_foto").click(function(){
		sound.play();
		Webcam.snap(function(data_uri) {
			Webcam.freeze();
			$("#snap_ok_div").show();
			$("#snap_foto_div").hide();
			if(window.innerHeight < window.innerWidth){
				$("#image_prev").attr("src", data_uri);
				$('#image_prev').croppie(
					{
						viewport:{
							height:320,
							width:240
						},
						showZoomer:false
					}
				);
			}else{
				data_uri_image = data_uri;
			}
		});
	});

	$("#snap_again").click(function(){
		Webcam.unfreeze();
		$("#snap_ok_div").hide();
		$("#snap_foto_div").show();
	});

	$("#snap_ok").click(function(){
		if (data_uri_image === "") {
			$("#image_prev").croppie('result', {
				type: 'base64',
				size: 'viewport',
				resultSize: {
					height:320,
					width:240
				}
			}).then(function (resp) {
				Webcam.reset();
				urltoFile(resp, 'a.png').then(function(file){
				    _files = file;
				});
				$("#image").attr("src", resp);
				$("#modal_take").hide();
			});
		}else{
			Webcam.reset();
			urltoFile(data_uri_image, 'a.png').then(function(file){
			    _files = file;
			});
			$("#image").attr("src", data_uri_image);
			$("#modal_take").hide();
		}
		

	});
	function imagePreview(input) {
	    if (input.files && input.files[0]) {
	    	if (input.files[0].size < 550000) {
				var reader = new FileReader();
		        reader.onload = function (e) {
		            $('#image').attr("src",e.target.result);
		        }
		        reader.readAsDataURL(input.files[0]);
		        _files = input.files[0];
		        // console.log(_files)
			}else{
				swal(
	                'Error',
	                'Maaf, resolusi foto terlalu besar. dan pastikan size kurang dari 500 kb',
	                'error'
	            )
			}
	    }else{
	    	swal(
	            'Error',
	            'Maaf, file tidak support',
	            'error'
	        )
	    }
	}
	$("#file").change(function () {
	    imagePreview(this);
	});
	$('#upload_test').click(function() {
		var fd = new FormData();
	    var files = _files;
	    fd.append('file',files);
	    fd.append('name', moment().unix().toString()+$("#nama").val().replace(/\s/g, '_'));
	    $.ajax({
	        url: 'upload_foto',
	        type: 'post',
	        data: fd,
	        contentType: false,
	        processData: false,
	        success: function(response){
	            console.log(response);
	        },

	    });
	});

});