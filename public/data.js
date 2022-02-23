$('#kabupaten').change(function () {
    var kabID = $(this).val();
    if (kabID) {
        $.ajax({
            type: "GET",
            url: "{{ route(user.prodi) }}?kabID" + kabID,
            dataType: 'JSON',
            success: function (res) {
                if (res) {
                    $("#kecamatan").empty();
                    $("#desa").empty();
                    $("#kecamatan").append('<option>---Pilih Kecamatan---</option>');
                    $("#desa").append('<option>---Pilih Desa---</option>');
                    $.each(res, function (nama, kode) {
                        $("#kecamatan").append('<option value="' + kode + '">' + nama + '</option>');
                    });
                } else {
                    $("#kecamatan").empty();
                    $("#desa").empty();
                }
            }
        });
    } else {
        $("#kecamatan").empty();
        $("#desa").empty();
    }
});
