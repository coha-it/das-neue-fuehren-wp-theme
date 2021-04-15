// Define dnf redirect
var dnf = {
    redirect: function (url) {
        jQuery( window ).load(function() {
            if (
                window.location.href.includes('/wp-admin/') || 
                window.location.hash.includes('admin')
                ) {
                return false;
        } else {
            window.location.href=url
        }
    })
    }
}

window.dnf = dnf;


function calcTime() {
    var now = new Date().getTime();
    var countDownDate = new Date(jQuery('[countdown]').attr('countdown')).getTime();
    var timeleft = countDownDate - now;

    if (timeleft > 0) {
            // Timer
            var days    = Math.floor(timeleft / (1000 * 60 * 60 * 24));
            var hours   = Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((timeleft % (1000 * 60)) / 1000);

            var msg = '';
            msg += days      ? days    + ' Tage ' : '';
            msg += hours     ? hours   + ' Stunden ' : '';
            msg += minutes   ? minutes + ' Minuten ' : '';
            msg += seconds + ' Sekunden ';
            msg += '';


            jQuery('[countdown]').html(msg);
            jQuery('[countdown-running]').show();
            jQuery('[countdown-finished]').hide();

            setTimeout(function() {
                calcTime();
            }, 1000);
        } else {
            // Timer Over!
            // console.log('Finished!');
            // Hide Countdown and Show Livestream
            jQuery('[countdown-running]').hide();
            jQuery('[countdown-finished]').show();
        }


    }

    jQuery( document ).ready(function() {
        calcTime();
    });