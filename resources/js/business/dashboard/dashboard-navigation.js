export default function() {
    const $dashboardNavigation = $('#dashboard-navigation');
    const $dashboardContent    = $('#dashboard-content');

    $dashboardNavigation
        .find('.card')
        .click(function() {
            let $this = $(this);
            let index = $this.parent().index();

            $dashboardNavigation.find('.card').removeClass('active');
            $this.addClass('active');

            $dashboardContent
                .find('.content-page').removeClass('active')
                .eq(index).addClass('active');
        });
}
