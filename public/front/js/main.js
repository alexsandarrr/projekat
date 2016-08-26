$(document).ready(function () {
    $(".filterProduct, .filter-wrapper").click(function () {
        $(".filter-wrapper").slideToggle('2000');
    });

    /*$(function () {
        $("#slider-range").slider({
            range: true,
            min: 0,
            max: 4000,
            values: [75, 3999],
            slide: function (event, ui) {
                $("#amount").val("$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ]);
            }
        });
        $("#amount").val("$" + $("#slider-range").slider("values", 0) +
                " - $" + $("#slider-range").slider("values", 1));
    });*/

    /*TABULATOR*/
    $(".artArchitecturePhotography").click(function () {
        $(".lists").slideToggle('2000');
    });
    $('#myTabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    /*SAKRIVANJE I POKAZIVANJE DIVA/CART*/
    $('.cart').click(function () {
        $('.second').slideToggle('2000');
    });

    $('.remove').click(function () {
        $(this).parent().parent().hide();
    });


    /*SAKRIVANJE I POKAZIVANJE LOZINKE*/
    $('.password span').click(function () {
        $(this).toggleClass('fa-eye  fa-eye-slash');

        var type = $(this).parent().siblings('input').attr('type');

        if (type == 'password') {
            $(this).parent().siblings().attr('type', 'text');
        } else {
            $(this).parent().siblings().attr('type', 'password');
        }
    });
    
    /*VALIDACIJA FORME*/
        $('#formUser').validator();
        
    $('a.legalEntity').click(function (){
        setTimeout(function(){ $('#formCompany').validator(); }, 10);
        
    });
    
});
