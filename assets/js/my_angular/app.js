angular.module('vahidapp', ['angular-loading-bar', 'ui-notification'])

	.config(function(NotificationProvider) {
        NotificationProvider.setOptions({
            delay: 3500,
            startTop: 20,
            startRight: 110,
            verticalSpacing: 20,
            horizontalSpacing: 20,
            positionX: 'right',
            positionY: 'bottom',
            replaceMessage: false
        });
		
		
    });