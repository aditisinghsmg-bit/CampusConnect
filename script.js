/* =========================================================
   CAMPUSCONNECT
   Main JavaScript
   ========================================================= */


/* =========================================================
   1. WAIT FOR DOM
   ========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    /* =====================================================
       2. MOBILE MENU
       ===================================================== */

    const mobileMenu = document.querySelector(".mobile-menu");
    const closeMenu = document.getElementById("closeMenu");

    /*
       Create mobile menu button dynamically if the
       desktop navbar doesn't already have one.
    */

    const navbar = document.querySelector(".navbar");

    if (navbar && !document.getElementById("mobileMenuBtn")) {

        const menuButton = document.createElement("button");

        menuButton.id = "mobileMenuBtn";
        menuButton.className = "mobile-menu-btn";

        menuButton.innerHTML =
            '<i class="fa-solid fa-bars"></i>';

        navbar.appendChild(menuButton);

        /*
           Open mobile menu
        */

        menuButton.addEventListener("click", function () {

            if (mobileMenu) {
                mobileMenu.classList.add("active");
                document.body.style.overflow = "hidden";
            }

        });

    }


    /*
       Close mobile menu
    */

    if (closeMenu) {

        closeMenu.addEventListener("click", function () {

            if (mobileMenu) {

                mobileMenu.classList.remove("active");

                document.body.style.overflow = "";

            }

        });

    }


    /*
       Close menu when clicking a navigation link
    */

    const mobileLinks =
        document.querySelectorAll(".mobile-nav-links a");

    mobileLinks.forEach(function (link) {

        link.addEventListener("click", function () {

            if (mobileMenu) {

                mobileMenu.classList.remove("active");

                document.body.style.overflow = "";

            }

        });

    });


    /* =====================================================
       3. CLOSE MOBILE MENU OUTSIDE
       ===================================================== */

    document.addEventListener("click", function (event) {

        if (!mobileMenu) {
            return;
        }

        const menuButton =
            document.getElementById("mobileMenuBtn");

        const clickedInsideMenu =
            mobileMenu.contains(event.target);

        const clickedMenuButton =
            menuButton &&
            menuButton.contains(event.target);

        if (
            !clickedInsideMenu &&
            !clickedMenuButton &&
            mobileMenu.classList.contains("active")
        ) {

            mobileMenu.classList.remove("active");

            document.body.style.overflow = "";

        }

    });


    /* =====================================================
       4. SCROLL TO TOP
       ===================================================== */

    const scrollTopBtn =
        document.getElementById("scrollTopBtn");

    if (scrollTopBtn) {

        window.addEventListener("scroll", function () {

            if (window.scrollY > 400) {

                scrollTopBtn.classList.add("show");

            } else {

                scrollTopBtn.classList.remove("show");

            }

        });


        scrollTopBtn.addEventListener("click", function () {

            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });

        });

    }


    /* =====================================================
       5. SEARCH FUNCTION
       ===================================================== */

    const searchInput =
        document.querySelector(".search-box input");

    const searchButton =
        document.querySelector(".search-box button");

    function performSearch() {

        if (!searchInput) {
            return;
        }

        const searchValue =
            searchInput.value.trim().toLowerCase();


        if (searchValue === "") {

            alert("Please enter something to search.");

            searchInput.focus();

            return;

        }


        /*
           If blogs.html exists, redirect the user there
           with the search query.
        */

        const searchURL =
            "blogs.html?search=" +
            encodeURIComponent(searchValue);

        window.location.href = searchURL;

    }


    if (searchButton) {

        searchButton.addEventListener(
            "click",
            performSearch
        );

    }


    if (searchInput) {

        searchInput.addEventListener(
            "keydown",
            function (event) {

                if (event.key === "Enter") {

                    event.preventDefault();

                    performSearch();

                }

            }
        );

    }


    /* =====================================================
       6. NEWSLETTER FORM
       ===================================================== */

    const newsletterForm =
        document.querySelector(".newsletter-form");


    if (newsletterForm) {

        newsletterForm.addEventListener(
            "submit",
            function (event) {

                event.preventDefault();

                const emailInput =
                    newsletterForm.querySelector(
                        'input[type="email"]'
                    );

                if (!emailInput) {
                    return;
                }

                const email =
                    emailInput.value.trim();


                /*
                   Basic email validation
                */

                const emailPattern =
                    /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


                if (!emailPattern.test(email)) {

                    showNotification(
                        "Please enter a valid email address.",
                        "error"
                    );

                    return;

                }


                /*
                   Save subscriber locally.
                   This will later be replaced by
                   backend/database functionality.
                */

                let subscribers =
                    JSON.parse(
                        localStorage.getItem(
                            "campusSubscribers"
                        )
                    ) || [];


                /*
                   Prevent duplicate subscriptions
                */

                if (subscribers.includes(email)) {

                    showNotification(
                        "You are already subscribed!",
                        "info"
                    );

                    return;

                }


                subscribers.push(email);


                localStorage.setItem(
                    "campusSubscribers",
                    JSON.stringify(subscribers)
                );


                emailInput.value = "";


                showNotification(
                    "Successfully subscribed to CampusConnect!",
                    "success"
                );

            }
        );

    }


    /* =====================================================
       7. NOTIFICATION SYSTEM
       ===================================================== */

    function showNotification(message, type = "success") {

        /*
           Remove existing notification
        */

        const oldNotification =
            document.querySelector(
                ".campus-notification"
            );

        if (oldNotification) {

            oldNotification.remove();

        }


        const notification =
            document.createElement("div");

        notification.className =
            "campus-notification";


        /*
           Select icon according to notification type
        */

        let icon =
            "fa-circle-check";

        if (type === "error") {

            icon = "fa-circle-xmark";

        }

        if (type === "info") {

            icon = "fa-circle-info";

        }


        notification.innerHTML = `

            <i class="fa-solid ${icon}"></i>

            <span>${message}</span>

            <button class="notification-close">
                <i class="fa-solid fa-xmark"></i>
            </button>

        `;


        document.body.appendChild(notification);


        /*
           Add notification styling dynamically
        */

        notification.style.position = "fixed";
        notification.style.top = "90px";
        notification.style.right = "25px";

        notification.style.zIndex = "5000";

        notification.style.display = "flex";
        notification.style.alignItems = "center";

        notification.style.gap = "12px";

        notification.style.padding = "14px 18px";

        notification.style.borderRadius = "10px";

        notification.style.background =
            "#ffffff";

        notification.style.color =
            "#1e293b";

        notification.style.boxShadow =
            "0 15px 40px rgba(0,0,0,0.15)";

        notification.style.border =
            "1px solid #e2e8f0";

        notification.style.fontSize =
            "13px";


        /*
           Icon color
        */

        const notificationIcon =
            notification.querySelector("i");

        if (type === "success") {

            notificationIcon.style.color =
                "#16a34a";

        }

        if (type === "error") {

            notificationIcon.style.color =
                "#dc2626";

        }

        if (type === "info") {

            notificationIcon.style.color =
                "#2563eb";

        }


        /*
           Close button
        */

        const closeButton =
            notification.querySelector(
                ".notification-close"
            );

        closeButton.style.background =
            "transparent";

        closeButton.style.color =
            "#64748b";

        closeButton.style.fontSize =
            "14px";


        closeButton.addEventListener(
            "click",
            function () {

                notification.remove();

            }
        );


        /*
           Automatically remove notification
        */

        setTimeout(function () {

            if (notification) {

                notification.style.opacity = "0";

                notification.style.transform =
                    "translateX(30px)";

                notification.style.transition =
                    "0.3s ease";

                setTimeout(function () {

                    notification.remove();

                }, 300);

            }

        }, 4000);

    }


    /* =====================================================
       8. CATEGORY CARD CLICK
       ===================================================== */

    const categoryCards =
        document.querySelectorAll(
            ".category-card"
        );


    categoryCards.forEach(function (card) {

        card.addEventListener(
            "click",
            function () {

                const category =
                    card.querySelector("h3");

                if (!category) {
                    return;
                }

                const categoryName =
                    category.textContent.trim();


                /*
                   Redirect to blogs page
                   with category filter
                */

                const categoryURL =
                    "blogs.html?category=" +
                    encodeURIComponent(categoryName);


                window.location.href =
                    categoryURL;

            }
        );

    });


    /* =====================================================
       9. BLOG CARD HOVER EFFECT
       ===================================================== */

    const blogCards =
        document.querySelectorAll(
            ".blog-card"
        );


    blogCards.forEach(function (card) {

        card.addEventListener(
            "mouseenter",
            function () {

                card.style.cursor = "pointer";

            }
        );

    });


    /* =====================================================
       10. EVENT DETAILS
       ===================================================== */

    const eventButtons =
        document.querySelectorAll(
            ".event-btn"
        );


    eventButtons.forEach(function (button) {

        button.addEventListener(
            "click",
            function (event) {

                /*
                   Prevent # from jumping to top
                */

                event.preventDefault();


                const eventCard =
                    button.closest(".event-card");


                if (!eventCard) {
                    return;
                }


                const title =
                    eventCard.querySelector(
                        ".event-details h3"
                    );


                if (!title) {
                    return;
                }


                showNotification(
                    "Event selected: " +
                    title.textContent.trim(),
                    "info"
                );

            }
        );

    });


    /* =====================================================
       11. ACTIVE NAVIGATION
       ===================================================== */

    const currentPage =
        window.location.pathname
            .split("/")
            .pop();


    const navLinks =
        document.querySelectorAll(
            ".nav-links a"
        );


    navLinks.forEach(function (link) {

        const linkPage =
            link.getAttribute("href");


        if (
            linkPage === currentPage ||
            (
                currentPage === "" &&
                linkPage === "index.html"
            )
        ) {

            navLinks.forEach(function (item) {

                item.classList.remove("active");

            });

            link.classList.add("active");

        }

    });


    /* =====================================================
       12. SCROLL REVEAL ANIMATION
       ===================================================== */

    const animatedElements =
        document.querySelectorAll(
            ".blog-card, " +
            ".category-card, " +
            ".achievement-card, " +
            ".feature-card, " +
            ".testimonial-card, " +
            ".event-card, " +
            ".trending-card"
        );


    /*
       Initial state
    */

    animatedElements.forEach(function (element) {

        element.style.opacity = "0";

        element.style.transform =
            "translateY(25px)";

        element.style.transition =
            "opacity 0.6s ease, transform 0.6s ease";

    });


    /*
       Intersection Observer
    */

    const observer =
        new IntersectionObserver(
            function (entries) {

                entries.forEach(function (entry) {

                    if (entry.isIntersecting) {

                        entry.target.style.opacity = "1";

                        entry.target.style.transform =
                            "translateY(0)";

                        observer.unobserve(
                            entry.target
                        );

                    }

                });

            },
            {
                threshold: 0.12
            }
        );


    animatedElements.forEach(function (element) {

        observer.observe(element);

    });


    /* =====================================================
       13. STATS COUNTER ANIMATION
       ===================================================== */

    const statNumbers =
        document.querySelectorAll(
            ".stat-card h2"
        );


    function animateCounter(element) {

        const originalText =
            element.textContent.trim();


        /*
           Extract number from text
           Example: 500+ => 500
        */

        const number =
            parseInt(
                originalText.replace(/\D/g, ""),
                10
            );


        if (isNaN(number)) {
            return;
        }


        const suffix =
            originalText.replace(
                /[0-9]/g,
                ""
            );


        let current = 0;

        const duration = 1500;

        const startTime =
            performance.now();


        function updateCounter(currentTime) {

            const elapsed =
                currentTime - startTime;


            const progress =
                Math.min(
                    elapsed / duration,
                    1
                );


            /*
               Ease-out animation
            */

            const ease =
                1 -
                Math.pow(
                    1 - progress,
                    3
                );


            current =
                Math.floor(
                    number * ease
                );


            element.textContent =
                current + suffix;


            if (progress < 1) {

                requestAnimationFrame(
                    updateCounter
                );

            } else {

                element.textContent =
                    number + suffix;

            }

        }


        requestAnimationFrame(
            updateCounter
        );

    }


    /*
       Animate counters only once
    */

    const statsSection =
        document.querySelector(
            ".stats"
        );


    if (statsSection && statNumbers.length) {

        const statsObserver =
            new IntersectionObserver(
                function (entries) {

                    entries.forEach(
                        function (entry) {

                            if (
                                entry.isIntersecting
                            ) {

                                statNumbers.forEach(
                                    function (number) {

                                        animateCounter(
                                            number
                                        );

                                    }
                                );


                                statsObserver.unobserve(
                                    statsSection
                                );

                            }

                        }
                    );

                },
                {
                    threshold: 0.4
                }
            );


        statsObserver.observe(
            statsSection
        );

    }


    /* =====================================================
       14. IMAGE ERROR HANDLING
       ===================================================== */

    const images =
        document.querySelectorAll(
            "img"
        );


    images.forEach(function (image) {

        image.addEventListener(
            "error",
            function () {

                /*
                   Prevent broken image icon
                */

                image.style.background =
                    "#e2e8f0";

                image.style.objectFit =
                    "cover";


                /*
                   Hide image if it cannot load
                   but preserve its dimensions.
                */

                image.style.color =
                    "#64748b";

            }
        );

    });


    /* =====================================================
       15. PREVENT EMPTY # LINKS
       ===================================================== */

    const hashLinks =
        document.querySelectorAll(
            'a[href="#"]'
        );


    hashLinks.forEach(function (link) {

        link.addEventListener(
            "click",
            function (event) {

                event.preventDefault();

            }
        );

    });


    /* =====================================================
       16. KEYBOARD ACCESSIBILITY
       ===================================================== */

    document.addEventListener(
        "keydown",
        function (event) {

            /*
               Escape closes mobile menu
            */

            if (
                event.key === "Escape" &&
                mobileMenu &&
                mobileMenu.classList.contains("active")
            ) {

                mobileMenu.classList.remove(
                    "active"
                );

                document.body.style.overflow =
                    "";

            }

        }
    );


    /* =====================================================
       17. PREVENT MULTIPLE FORM SUBMISSIONS
       ===================================================== */

    const forms =
        document.querySelectorAll(
            "form"
        );


    forms.forEach(function (form) {

        form.addEventListener(
            "submit",
            function () {

                const submitButton =
                    form.querySelector(
                        'button[type="submit"]'
                    );


                if (!submitButton) {
                    return;
                }


                /*
                   Only temporarily disable buttons
                   for forms that aren't newsletter.
                */

                if (
                    !form.classList.contains(
                        "newsletter-form"
                    )
                ) {

                    setTimeout(function () {

                        submitButton.disabled =
                            false;

                    }, 1500);

                }

            }
        );

    });


    /* =====================================================
       18. PAGE LOAD
       ===================================================== */

    document.body.classList.add(
        "page-loaded"
    );


    console.log(
        "CampusConnect loaded successfully!"
    );

});