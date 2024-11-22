jQuery(document).ready(function ($) {
     // Define the function to update dots visibility
    function updateDots(slick) {
        const maxDots = 4;
        const $dots = $('.slick-dots li');
        const totalDots = $dots.length;

        if ($(window).width() <= 600) {
            $dots.show();
            if (totalDots > maxDots) {
                $dots.each(function (index) {
                    if (index >= maxDots) {
                        $(this).hide();
                    }
                });
            }
        } else {
            $dots.hide(); // Ensure dots are hidden on larger screens
        }
    }

    // Helper function to determine if dots should be updated
    function shouldUpdateDots() {
        return $(window).width() <= 600;
    }

    // Initialize Slick slider
    $('.events-slider').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        prevArrow: '<button class="carousel-control-prev top-50 start-0 translate-middle" type="button"><i class="bi bi-arrow-left-circle-fill"></i><span class="visually-hidden">Previous</span></button>',
        nextArrow: '<button class="carousel-control-next top-50 start-100 translate-middle" type="button"><i class="bi bi-arrow-right-circle-fill"></i><span class="visually-hidden">Next</span></button>',
        dots: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                    arrows: true,
                    dots: false
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,
                    arrows: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    }).on('init', function (event, slick) {
        if (shouldUpdateDots()) {
            updateDots(slick); // Ensure this function is called only if necessary
        }
    }).on('setPosition', function (event, slick) {
        if (shouldUpdateDots()) {
            updateDots(slick);
        }
    });

    // Handle window resize to adjust dots
    $(window).resize(function () {
        if (shouldUpdateDots()) {
            const slick = $('.events-slider').slick('getSlick');
            updateDots(slick);
        } else {
            $('.slick-dots li').hide(); // Ensure dots are hidden on larger screens
        }
    });

    // Find the navbar-nav unordered list
    var navbarNav = document.querySelector('.navbar-nav');
    if (navbarNav) {
        var listItems = navbarNav.querySelectorAll('li');
        listItems.forEach(function (listItem) {
            listItem.classList.add('nav-item');
            var anchorTag = listItem.querySelector('a');
            if (anchorTag) {
                anchorTag.classList.add('nav-link');
            }
        });
    } else {
        console.warn('.navbar-nav element not found');
    }

    // Find the parent menu item that contains the submenu
    var parentMenuItem = document.querySelector('.menu-item-has-children');
    if (parentMenuItem) {
        var subMenu = parentMenuItem.querySelector('.sub-menu');
        parentMenuItem.classList.add('dropdown');
        if (subMenu) {
            subMenu.classList.add('dropdown-menu', 'text-center', 'dropdown-menu-end');
        }
        var dropdownSubMenu = document.querySelector('.sub-menu.dropdown-menu');
        if (dropdownSubMenu) {
            var anchorTags = subMenu.querySelectorAll('a');
            anchorTags.forEach(function (anchorTag) {
                anchorTag.classList.add('dropdown-item');
            });
        }
        var parentAnchorTag = parentMenuItem.querySelector('a');
        if (parentAnchorTag) {
            parentAnchorTag.setAttribute('role', 'button');
            parentAnchorTag.setAttribute('data-bs-toggle', 'dropdown');
            parentAnchorTag.setAttribute('data-bs-auto-close', 'true');
            parentAnchorTag.setAttribute('aria-expanded', 'false');
            parentAnchorTag.setAttribute('href', '#');
            parentAnchorTag.setAttribute('id', 'loginDropdown');
            parentAnchorTag.classList.add('dropdown-toggle');
        }
    } else {
        console.warn('.menu-item-has-children element not found');
    }

    // Find all <i> tags inside the element with class "socials"
    var icons = document.querySelectorAll('.socials i');
    icons.forEach(function (icon) {
        icon.classList.add('fa-2xl');
    });

    // Find the element with the class "menu-footer-container"
    var menuFooterContainer = document.querySelector('.menu-footer-container');
    if (menuFooterContainer) {
        menuFooterContainer.classList.add('container');
    }

    // Listen for the shown.bs.dropdown event
    $('#loginDropdown').on('shown.bs.dropdown', function () {
        $(this).addClass('show');
    });

    // Listen for the hidden.bs.dropdown event
    $('#loginDropdown').on('hidden.bs.dropdown', function () {
        $(this).removeClass('show');
    });

    var btn = $('#button');
    $(window).scroll(function () {
        if ($(window).scrollTop() > 300) {
            btn.addClass('show');
        } else {
            btn.removeClass('show');
        }
    });

    btn.on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, '300');
    });

    // Load the animation JSON file
    var jsonPath = $('#logo-animation').data('json');
    $.getJSON(jsonPath, function(animationData) {
        var animationOptions = {
            container: document.getElementById('logo-animation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            animationData: animationData
        };
        var anim = lottie.loadAnimation(animationOptions);
    }).fail(function() {
        // If the animation fails to load, show the logo
        var logoContainer = document.getElementById('logo-animation');
        if (logoContainer) {
            logoContainer.classList.add('animation-failed');
        }
    });
});