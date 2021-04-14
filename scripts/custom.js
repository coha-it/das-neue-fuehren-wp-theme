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

