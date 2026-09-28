/* ==========================================================================
   گروه ساختمانی طهورنیان — انیمیشن‌ها و تعاملات سایت
   وابستگی‌ها (از CDN): GSAP + ScrollTrigger (+Flip در صفحه پروژه‌ها)، Swiper، Lenis
   همه ماژول‌ها در صورت بارگذاری نشدن کتابخانه‌ها، بدون خطا غیرفعال می‌شوند.
   ========================================================================== */
(function () {
    'use strict';

    const html = document.documentElement;
    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));
    const hasGsap = typeof window.gsap !== 'undefined';
    const hasST = hasGsap && typeof window.ScrollTrigger !== 'undefined';
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isTouch = window.matchMedia('(hover: none)').matches;
    const faDigits = (v) => String(v).replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]);

    if (hasGsap) {
        if (hasST) gsap.registerPlugin(ScrollTrigger);
        if (window.Flip) gsap.registerPlugin(Flip);
        if (!reduced && hasST) html.classList.add('gsap-ready');
    }

    /* ---------------------------------------------------------------
       اسکرول نرم (Lenis)
    --------------------------------------------------------------- */
    let lenis = null;
    if (window.Lenis && !reduced && !isTouch) {
        lenis = new Lenis({ duration: 1.15, easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), smoothWheel: true });
        if (hasGsap) {
            if (hasST) lenis.on('scroll', ScrollTrigger.update);
            gsap.ticker.add((time) => lenis.raf(time * 1000));
            gsap.ticker.lagSmoothing(0);
        } else {
            const raf = (time) => { lenis.raf(time); requestAnimationFrame(raf); };
            requestAnimationFrame(raf);
        }
    }
    const scrollToY = (y) => (lenis ? lenis.scrollTo(y, { duration: 1.6 }) : window.scrollTo({ top: y, behavior: 'smooth' }));

    /* ---------------------------------------------------------------
       شکستن متن به کلمات (حروف فارسی جدا نمی‌شوند تا اتصال حروف حفظ شود)
    --------------------------------------------------------------- */
    function splitWords(el) {
        if (el.dataset.splitDone) return $$('.split-inner', el);
        const walk = (node) => {
            Array.from(node.childNodes).forEach((child) => {
                if (child.nodeType === 3) {
                    const parts = child.textContent.split(/(\s+)/);
                    const frag = document.createDocumentFragment();
                    parts.forEach((part) => {
                        if (!part) return;
                        if (/^\s+$/.test(part)) { frag.appendChild(document.createTextNode(' ')); return; }
                        const w = document.createElement('span');
                        w.className = 'split-word';
                        w.style.cssText = 'display:inline-block;overflow:hidden;vertical-align:top;padding-bottom:.12em;margin-bottom:-.12em';
                        const inner = document.createElement('span');
                        inner.className = 'split-inner';
                        inner.textContent = part;
                        w.appendChild(inner);
                        frag.appendChild(w);
                    });
                    child.replaceWith(frag);
                } else if (child.nodeType === 1 && child.tagName !== 'BR') {
                    walk(child);
                }
            });
        };
        walk(el);
        el.dataset.splitDone = '1';
        el.classList.add('is-split');
        return $$('.split-inner', el);
    }

    /* ---------------------------------------------------------------
       پیش‌بارگذار
    --------------------------------------------------------------- */
    const ready = new Promise((resolve) => {
        const pre = $('.preloader');
        if (!pre || !html.classList.contains('is-loading')) { html.classList.remove('is-loading'); resolve(); return; }

        const countEl = $('.preloader__count', pre);
        const bar = $('.preloader__bar span', pre);
        const fast = sessionStorage.getItem('visited') === '1';
        sessionStorage.setItem('visited', '1');
        const duration = fast ? 500 : 1700;
        let done = false;

        const finish = () => {
            if (done) return;
            done = true;
            if (hasGsap && !reduced) {
                gsap.timeline({ onComplete: () => { html.classList.remove('is-loading'); resolve(); } })
                    .to('.preloader__inner', { y: -40, opacity: 0, duration: .5, ease: 'power3.in' })
                    .to('.preloader__panel', { scaleY: 1, duration: .6, ease: 'power4.inOut' }, '-=.1')
                    .to(pre, { yPercent: -100, duration: .8, ease: 'power4.inOut' })
                    .add(() => resolve(), '-=.45');
            } else {
                html.classList.remove('is-loading');
                resolve();
            }
        };

        const start = performance.now();
        const tick = (now) => {
            const p = Math.min(1, (now - start) / duration);
            const eased = 1 - Math.pow(1 - p, 3);
            if (countEl) countEl.textContent = faDigits(Math.round(eased * 100));
            if (bar) bar.style.width = eased * 100 + '%';
            if (p < 1) requestAnimationFrame(tick);
            else if (document.readyState === 'complete') finish();
            else window.addEventListener('load', finish, { once: true });
        };
        requestAnimationFrame(tick);
        setTimeout(finish, 5000); // اطمینان از حذف پیش‌بارگذار
    });

    /* ---------------------------------------------------------------
       انتقال بین صفحات
    --------------------------------------------------------------- */
    function initPageTransition() {
        const pt = $('.page-transition');
        if (!pt || !hasGsap || reduced) return;
        const panels = $$('span', pt);

        gsap.set(panels, { scaleY: 1, transformOrigin: 'bottom' });
        gsap.to(panels, { scaleY: 0, duration: .8, ease: 'power4.inOut', stagger: .08, delay: .05 });

        document.addEventListener('click', (e) => {
            const a = e.target.closest('a');
            if (!a || e.defaultPrevented || e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
            const href = a.getAttribute('href');
            if (!href || href.startsWith('#') || a.target === '_blank' || a.hasAttribute('download') || a.dataset.noTransition !== undefined) return;
            if (a.hasAttribute('data-lightbox') || a.hasAttribute('data-video')) return;
            const url = new URL(a.href, location.href);
            if (url.origin !== location.origin || url.pathname.startsWith('/admin')) return;
            if (url.pathname === location.pathname && url.hash) return;
            e.preventDefault();
            gsap.set(panels, { transformOrigin: 'top' });
            gsap.to(panels, { scaleY: 1, duration: .6, ease: 'power4.inOut', stagger: .06, onComplete: () => { location.href = a.href; } });
        });

        window.addEventListener('pageshow', (e) => {
            if (e.persisted) gsap.set(panels, { scaleY: 0 });
        });
    }

    /* ---------------------------------------------------------------
       نشانگر سفارشی ماوس
    --------------------------------------------------------------- */
    function initCursor() {
        const cursor = $('.cursor');
        const dot = $('.cursor-dot');
        if (!cursor || !dot || isTouch || !hasGsap) return;
        const text = $('.cursor__text', cursor);

        const xTo = gsap.quickTo(cursor, 'x', { duration: .5, ease: 'power3' });
        const yTo = gsap.quickTo(cursor, 'y', { duration: .5, ease: 'power3' });
        const dxTo = gsap.quickTo(dot, 'x', { duration: .08 });
        const dyTo = gsap.quickTo(dot, 'y', { duration: .08 });

        cursor.classList.add('is-hidden');
        dot.classList.add('is-hidden');
        let moved = false;
        window.addEventListener('mousemove', (e) => {
            if (!moved) {
                moved = true;
                gsap.set([cursor, dot], { x: e.clientX, y: e.clientY });
                cursor.classList.remove('is-hidden'); dot.classList.remove('is-hidden');
            }
            xTo(e.clientX); yTo(e.clientY); dxTo(e.clientX); dyTo(e.clientY);
        });
        document.addEventListener('mouseleave', () => { cursor.classList.add('is-hidden'); dot.classList.add('is-hidden'); });
        document.addEventListener('mouseenter', () => { cursor.classList.remove('is-hidden'); dot.classList.remove('is-hidden'); });

        document.addEventListener('mouseover', (e) => {
            const labeled = e.target.closest('[data-cursor]');
            if (labeled) {
                text.textContent = labeled.dataset.cursor;
                cursor.classList.add('has-text');
                dot.classList.add('is-hidden');
                return;
            }
            if (e.target.closest('a, button, input, textarea, select, label, .swiper-slide')) cursor.classList.add('is-hover');
        });
        document.addEventListener('mouseout', (e) => {
            const labeled = e.target.closest('[data-cursor]');
            if (labeled && !labeled.contains(e.relatedTarget)) {
                cursor.classList.remove('has-text');
                dot.classList.remove('is-hidden');
            }
            if (e.target.closest('a, button, input, textarea, select, label, .swiper-slide')) cursor.classList.remove('is-hover');
        });
    }

    /* ---------------------------------------------------------------
       هدر و منو
    --------------------------------------------------------------- */
    function initHeader() {
        const header = $('#siteHeader');
        const burger = $('#burger');
        const overlay = $('#menuOverlay');
        let lastY = 0;
        let menuOpen = false;

        const onScroll = () => {
            const y = window.scrollY;
            if (!header) return;
            header.classList.toggle('is-scrolled', y > 40 || document.body.classList.contains('header-solid'));
            const delta = y - lastY;
            if (Math.abs(delta) < 8) return;
            header.classList.toggle('is-hidden', !menuOpen && delta > 0 && y > 400);
            lastY = y;
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        if (burger && overlay) {
            const toggle = (state) => {
                menuOpen = state ?? !menuOpen;
                burger.classList.toggle('is-open', menuOpen);
                overlay.classList.toggle('is-open', menuOpen);
                burger.setAttribute('aria-expanded', menuOpen);
                if (lenis) menuOpen ? lenis.stop() : lenis.start();
                document.body.style.overflow = menuOpen ? 'hidden' : '';
            };
            burger.addEventListener('click', () => toggle());
            $$('a', overlay).forEach((a) => a.addEventListener('click', () => toggle(false)));
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && menuOpen) toggle(false); });
        }

        // لینک‌های لنگر داخل صفحه
        $$('a[href^="#"]').forEach((a) => {
            a.addEventListener('click', (e) => {
                const id = a.getAttribute('href');
                if (id.length < 2) return;
                const target = $(id);
                if (!target) return;
                e.preventDefault();
                scrollToY(target.getBoundingClientRect().top + window.scrollY - 80);
            });
        });
    }

    /* ---------------------------------------------------------------
       اسلایدر هیرو
    --------------------------------------------------------------- */
    function initHero() {
        const el = $('.hero .swiper');
        if (!el) return;
        const dots = $$('.hero-dots button');

        const animateSlide = (slide) => {
            if (!slide || !hasGsap || reduced) return;
            const q = (s) => $$(s, slide);
            gsap.timeline()
                .fromTo(q('.hero-tags span'), { y: 24, opacity: 0 }, { y: 0, opacity: 1, duration: .7, ease: 'power3.out', stagger: .08 })
                .fromTo(q('.hero-slide__title .line > span'), { yPercent: 115 }, { yPercent: 0, duration: 1.1, ease: 'power4.out', stagger: .12 }, '-=.45')
                .fromTo(q('.hero-designer'), { x: 40, opacity: 0 }, { x: 0, opacity: 1, duration: .8, ease: 'power3.out' }, '-=.7')
                .fromTo(q('.hero-slide__desc'), { y: 24, opacity: 0 }, { y: 0, opacity: 1, duration: .8, ease: 'power3.out' }, '-=.6')
                .fromTo(q('.hero-slide__actions > *'), { y: 24, opacity: 0 }, { y: 0, opacity: 1, duration: .7, ease: 'power3.out', stagger: .1 }, '-=.6')
                .fromTo(q('.hero-arch'), { clipPath: 'inset(100% 0% 0% 0% round 999px 999px 0 0)' }, { clipPath: 'inset(0% 0% 0% 0% round 999px 999px 0 0)', duration: 1.4, ease: 'power4.inOut' }, 0)
                .fromTo(q('.hero-arch img'), { scale: 1.3 }, { scale: 1, duration: 1.8, ease: 'power3.out' }, 0)
                .fromTo(q('.hero-diamond'), { scale: .4, opacity: 0, rotate: 0 }, { scale: 1, opacity: 1, rotate: 45, duration: 1.4, ease: 'back.out(1.6)' }, .2)
                .fromTo(q('.hero-leaf'), { scale: 0 }, { scale: 1, duration: 1, ease: 'back.out(2)', stagger: .15 }, .6);
        };

        const setDots = (index) => dots.forEach((d, i) => d.classList.toggle('is-active', i === index));

        if (typeof window.Swiper === 'undefined') {
            ready.then(() => animateSlide($('.hero-slide', el)));
            return;
        }

        const slidesCount = $$('.swiper-slide', el).length;
        const swiper = new Swiper(el, {
            effect: 'fade',
            fadeEffect: { crossFade: true },
            speed: 900,
            loop: slidesCount > 1,
            allowTouchMove: slidesCount > 1,
            autoHeight: false,
            autoplay: slidesCount > 1 ? { delay: 7000, disableOnInteraction: false } : false,
            navigation: { nextEl: '.hero-next', prevEl: '.hero-prev' },
            on: {
                slideChangeTransitionStart(s) {
                    setDots(s.realIndex);
                    animateSlide(s.slides[s.activeIndex]);
                },
                autoplayTimeLeft(s, time, pct) {
                    const active = dots[s.realIndex];
                    if (active) active.style.setProperty('--p', 1 - pct);
                },
            },
        });
        dots.forEach((d, i) => d.addEventListener('click', () => (slidesCount > 1 ? swiper.slideToLoop(i) : null)));
        swiper.autoplay && swiper.autoplay.stop && swiper.autoplay.stop();

        ready.then(() => {
            animateSlide(swiper.slides[swiper.activeIndex]);
            if (slidesCount > 1) swiper.autoplay.start();
            if (hasGsap && !reduced) gsap.from('.hero-ui', { opacity: 0, y: 30, duration: 1, delay: .9, ease: 'power3.out' });
        });

        if (hasST && !reduced) {
            gsap.to('.hero-slide__media', { yPercent: 12, ease: 'none', scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: true } });
            gsap.to('.hero-slide__content', { opacity: 0, y: -60, ease: 'none', scrollTrigger: { trigger: '.hero', start: '10% top', end: '70% top', scrub: true } });
        }
    }

    /* ---------------------------------------------------------------
       نور دنبال‌کننده ماوس روی پس‌زمینه‌های شبکه‌ای
    --------------------------------------------------------------- */
    function initGridSpot() {
        if (isTouch) return;
        $$('[data-grid-spot]').forEach((el) => {
            el.addEventListener('mousemove', (e) => {
                const r = el.getBoundingClientRect();
                el.style.setProperty('--mx', (e.clientX - r.left) + 'px');
                el.style.setProperty('--my', (e.clientY - r.top) + 'px');
            });
        });
    }

    /* ---------------------------------------------------------------
       فروشگاه: افزودن به سبد، علاقه‌مندی، تعداد، گالری
    --------------------------------------------------------------- */
    const toastEl = $('.site-toast');
    let toastTimer;
    function toast(message, isError = false, link = null) {
        if (!toastEl) return;
        $('span', toastEl).innerHTML = '';
        $('span', toastEl).textContent = message;
        if (link) {
            const a = document.createElement('a');
            a.href = link.href; a.textContent = link.label;
            $('span', toastEl).appendChild(a);
        }
        $('i', toastEl).className = isError ? 'ri-error-warning-fill' : 'ri-checkbox-circle-fill';
        toastEl.classList.toggle('is-error', isError);
        toastEl.classList.add('is-visible');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toastEl.classList.remove('is-visible'), 4200);
    }

    function flyToCart(img) {
        const cart = $('#cartIcon');
        if (!img || !cart || !hasGsap || reduced) return;
        const from = img.getBoundingClientRect();
        const to = cart.getBoundingClientRect();
        const clone = img.cloneNode();
        clone.className = 'fly-img';
        Object.assign(clone.style, { left: from.left + 'px', top: from.top + 'px', width: from.width + 'px', height: from.height + 'px' });
        document.body.appendChild(clone);
        gsap.timeline({ onComplete: () => clone.remove() })
            .to(clone, { duration: .35, scale: .8, ease: 'power2.out' })
            .to(clone, {
                duration: .9, ease: 'power3.inOut',
                left: to.left + to.width / 2 - 20, top: to.top + to.height / 2 - 20, width: 40, height: 40, scale: 1, borderRadius: '50%', opacity: .6,
            });
    }

    function initShop() {
        document.addEventListener('submit', async (e) => {
            const form = e.target.closest('.js-add-cart');
            if (!form || !window.fetch) return;
            e.preventDefault();
            const btn = $('button[type=submit]', form);
            btn && btn.classList.add('is-loading');
            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(form),
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(data.message || 'خطا در افزودن به سبد خرید');
                const card = form.closest('.product-card');
                flyToCart(card ? $('.product-card__media img', card) : $('#pdMain'));
                setTimeout(() => {
                    $$('.cart-count').forEach((c) => {
                        c.dataset.count = data.count;
                        c.textContent = faDigits(data.count);
                        c.classList.remove('bump'); void c.offsetWidth; c.classList.add('bump');
                    });
                }, 900);
                toast(data.message, false, { href: '/cart', label: 'مشاهده سبد' });
            } catch (err) {
                toast(err.message || 'خطایی رخ داد', true);
            }
            btn && btn.classList.remove('is-loading');
        });

        // علاقه‌مندی‌ها (ذخیره در مرورگر)
        let favs = [];
        try { favs = JSON.parse(localStorage.getItem('favs') || '[]'); } catch (e) {}
        const sync = () => $$('.js-fav').forEach((b) => b.classList.toggle('is-active', favs.includes(b.dataset.id)));
        sync();
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.js-fav');
            if (!btn) return;
            const id = btn.dataset.id;
            favs = favs.includes(id) ? favs.filter((f) => f !== id) : [...favs, id];
            try { localStorage.setItem('favs', JSON.stringify(favs)); } catch (err) {}
            sync();
            $('i', btn).className = btn.classList.contains('is-active') ? 'ri-heart-3-fill' : 'ri-heart-3-line';
            if (hasGsap) gsap.fromTo(btn, { scale: .6 }, { scale: 1, duration: .6, ease: 'elastic.out(1,.4)' });
            toast(btn.classList.contains('is-active') ? 'به علاقه‌مندی‌ها اضافه شد' : 'از علاقه‌مندی‌ها حذف شد');
        });
        $$('.js-fav.is-active i').forEach((i) => { i.className = 'ri-heart-3-fill'; });

        // کنترل تعداد
        $$('[data-qty]').forEach((box) => {
            const input = $('input', box);
            $$('[data-step]', box).forEach((b) => b.addEventListener('click', () => {
                const min = parseInt(input.min || '0', 10);
                input.value = Math.max(min, Math.min(99, (parseInt(input.value, 10) || 0) + parseInt(b.dataset.step, 10)));
                if (box.hasAttribute('data-autosubmit')) box.submit();
            }));
            if (box.hasAttribute('data-autosubmit')) input.addEventListener('change', () => box.submit());
        });

        // گالری محصول
        const main = $('#pdMain');
        $$('.pd-gallery__thumbs button').forEach((b) => b.addEventListener('click', () => {
            $$('.pd-gallery__thumbs button').forEach((x) => x.classList.toggle('is-active', x === b));
            if (hasGsap) gsap.fromTo(main, { opacity: 0, scale: 1.05 }, { opacity: 1, scale: 1, duration: .6 });
            main.src = b.dataset.src;
        }));

        // پیام‌های فلش
        if (toastEl && (toastEl.dataset.flash || toastEl.dataset.flashError)) {
            ready.then(() => setTimeout(() => toast(toastEl.dataset.flash || toastEl.dataset.flashError, !toastEl.dataset.flash), 400));
        }
    }

    /* ---------------------------------------------------------------
       تایم‌لاین و کلاژ تیم
    --------------------------------------------------------------- */
    function initTimeline() {
        const steps = $$('[data-tl]');
        steps.forEach((step) => step.addEventListener('mouseenter', () => steps.forEach((s) => s.classList.toggle('is-open', s === step))));
        steps.forEach((step) => step.addEventListener('click', () => steps.forEach((s) => s.classList.toggle('is-open', s === step))));
        if (!hasST || reduced || !steps.length) return;
        steps.forEach((step, i) => {
            gsap.fromTo(step, { clipPath: i % 2 ? 'inset(0 0 0 100%)' : 'inset(0 100% 0 0)' }, {
                clipPath: 'inset(0 0% 0 0%)', duration: 1.2, ease: 'power4.inOut',
                scrollTrigger: { trigger: step, start: 'top 88%' },
            });
            gsap.fromTo($('.tl-step__box', step), { y: 40, opacity: 0 }, { y: 0, opacity: 1, duration: .9, delay: .4, ease: 'power3.out', scrollTrigger: { trigger: step, start: 'top 88%' } });
        });
        $$('.tl-arrow').forEach((a) => gsap.fromTo(a, { scale: 0, rotate: -90 }, { scale: 1, rotate: 0, duration: .8, ease: 'back.out(2)', scrollTrigger: { trigger: a, start: 'top 90%' } }));
        $$('[data-parallax-y]').forEach((el) => {
            const sp = parseFloat(el.dataset.parallaxY) || .1;
            gsap.fromTo(el, { y: sp * 260 }, { y: -sp * 120, ease: 'none', scrollTrigger: { trigger: el.closest('section'), start: 'top bottom', end: 'bottom top', scrub: true } });
        });
    }

    /* ---------------------------------------------------------------
       بوم نقشه معماری (شبکه نقاط متصل) در هیرو
    --------------------------------------------------------------- */
    function initBlueprint() {
        const canvas = $('.hero-canvas');
        if (!canvas || reduced) return;
        const ctx = canvas.getContext('2d');
        let w, h, dpr, points = [];
        const mouse = { x: -9999, y: -9999 };
        const gold = '201,161,91';

        const resize = () => {
            dpr = Math.min(window.devicePixelRatio || 1, 2);
            w = canvas.clientWidth; h = canvas.clientHeight;
            canvas.width = w * dpr; canvas.height = h * dpr;
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            const count = Math.round((w * h) / 26000);
            points = Array.from({ length: count }, () => ({
                x: Math.random() * w, y: Math.random() * h,
                vx: (Math.random() - .5) * .25, vy: (Math.random() - .5) * .25,
                r: Math.random() * 1.6 + .6,
            }));
        };

        let visible = true;
        const io = new IntersectionObserver(([entry]) => { visible = entry.isIntersecting; });
        io.observe(canvas);

        const draw = () => {
            requestAnimationFrame(draw);
            if (!visible) return;
            ctx.clearRect(0, 0, w, h);

            // خطوط راهنمای معماری
            ctx.strokeStyle = `rgba(${gold},.05)`;
            ctx.lineWidth = 1;
            for (let x = 0; x < w; x += 120) { ctx.beginPath(); ctx.moveTo(x, 0); ctx.lineTo(x, h); ctx.stroke(); }
            for (let y = 0; y < h; y += 120) { ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(w, y); ctx.stroke(); }

            for (let i = 0; i < points.length; i++) {
                const p = points[i];
                p.x += p.vx; p.y += p.vy;
                if (p.x < 0 || p.x > w) p.vx *= -1;
                if (p.y < 0 || p.y > h) p.vy *= -1;

                const mdx = p.x - mouse.x, mdy = p.y - mouse.y;
                const md = Math.hypot(mdx, mdy);
                if (md < 140) { p.x += mdx / md * 1.2; p.y += mdy / md * 1.2; }

                for (let j = i + 1; j < points.length; j++) {
                    const q = points[j];
                    const d = Math.hypot(p.x - q.x, p.y - q.y);
                    if (d < 150) {
                        ctx.strokeStyle = `rgba(${gold},${(1 - d / 150) * .35})`;
                        ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(q.x, q.y); ctx.stroke();
                    }
                }
                ctx.fillStyle = `rgba(${gold},.8)`;
                ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2); ctx.fill();
            }
        };

        window.addEventListener('resize', resize);
        canvas.parentElement.addEventListener('mousemove', (e) => {
            const r = canvas.getBoundingClientRect();
            mouse.x = e.clientX - r.left; mouse.y = e.clientY - r.top;
        });
        canvas.parentElement.addEventListener('mouseleave', () => { mouse.x = mouse.y = -9999; });
        resize();
        draw();
    }

    /* ---------------------------------------------------------------
       انیمیشن‌های اسکرول
    --------------------------------------------------------------- */
    function initScrollAnimations() {
        if (!hasST || reduced) return;

        // عناوین با نمایش کلمه به کلمه
        $$('[data-split]').forEach((el) => {
            const words = splitWords(el);
            gsap.fromTo(words, { yPercent: 110, opacity: 0 }, {
                yPercent: 0, opacity: 1, duration: 1.1, ease: 'power4.out', stagger: .045,
                scrollTrigger: { trigger: el, start: 'top 88%' },
            });
        });

        // نمایش تدریجی عناصر
        const from = {
            up: { y: 60, opacity: 0 },
            down: { y: -60, opacity: 0 },
            left: { x: -80, opacity: 0 },
            right: { x: 80, opacity: 0 },
            scale: { scale: .85, opacity: 0 },
            fade: { opacity: 0 },
            blur: { opacity: 0, filter: 'blur(12px)', y: 30 },
        };
        $$('[data-reveal]').forEach((el) => {
            const type = el.dataset.reveal || 'up';
            const vars = from[type] || from.up;
            gsap.fromTo(el, vars, {
                x: 0, y: 0, scale: 1, opacity: 1, filter: 'blur(0px)',
                duration: parseFloat(el.dataset.duration || 1.1),
                delay: parseFloat(el.dataset.delay || 0),
                ease: 'power3.out',
                scrollTrigger: { trigger: el, start: 'top 90%' },
                clearProps: 'filter',
            });
        });

        // گروه‌ها با فاصله زمانی
        $$('[data-stagger]').forEach((group) => {
            const items = group.children;
            gsap.fromTo(items, { y: 70, opacity: 0 }, {
                y: 0, opacity: 1, duration: 1, ease: 'power3.out', stagger: parseFloat(group.dataset.stagger) || .12,
                scrollTrigger: { trigger: group, start: 'top 85%' },
            });
        });

        // نمایش تصاویر با ماسک
        $$('[data-img-reveal]').forEach((wrap) => {
            const img = $('img', wrap);
            const dir = wrap.dataset.imgReveal || 'up';
            const clips = {
                up: 'inset(100% 0% 0% 0%)',
                down: 'inset(0% 0% 100% 0%)',
                left: 'inset(0% 0% 0% 100%)',
                right: 'inset(0% 100% 0% 0%)',
            };
            const tl = gsap.timeline({ scrollTrigger: { trigger: wrap, start: 'top 85%' } });
            tl.fromTo(wrap, { clipPath: clips[dir] || clips.up }, { clipPath: 'inset(0% 0% 0% 0%)', duration: 1.5, ease: 'power4.inOut' });
            if (img) tl.fromTo(img, { scale: 1.35 }, { scale: 1, duration: 1.8, ease: 'power3.out' }, 0);
        });

        // پارالاکس
        $$('[data-parallax]').forEach((el) => {
            const speed = parseFloat(el.dataset.parallax) || .2;
            gsap.fromTo(el, { yPercent: -speed * 50 }, {
                yPercent: speed * 50, ease: 'none',
                scrollTrigger: { trigger: el.parentElement, start: 'top bottom', end: 'bottom top', scrub: true },
            });
        });

        // متن با برجسته‌سازی تدریجی کلمات
        $$('[data-highlight]').forEach((el) => {
            const words = splitWords(el);
            $$('.split-word', el).forEach((w) => { w.style.overflow = 'visible'; });
            gsap.fromTo(words, { opacity: .18 }, {
                opacity: 1, ease: 'none', stagger: .08,
                scrollTrigger: { trigger: el, start: 'top 80%', end: 'bottom 45%', scrub: true },
            });
        });

        // شمارنده‌ها
        $$('[data-counter]').forEach((el) => {
            const target = parseFloat(el.dataset.counter) || 0;
            const obj = { v: 0 };
            el.textContent = faDigits(0);
            gsap.to(obj, {
                v: target, duration: 2.4, ease: 'power2.out',
                scrollTrigger: { trigger: el, start: 'top 90%' },
                onUpdate: () => { el.textContent = faDigits(Math.round(obj.v).toLocaleString('en-US').replace(/,/g, '٬')); },
            });
        });

        // خط مراحل کار
        $$('.process-line').forEach((line) => {
            gsap.fromTo(line, { scaleX: 0, transformOrigin: 'right' }, {
                scaleX: 1, ease: 'none',
                scrollTrigger: { trigger: line.parentElement, start: 'top 75%', end: 'bottom 60%', scrub: 1 },
            });
        });

        // متن بزرگ فوتر
        const big = $('.footer-big');
        if (big) {
            gsap.fromTo(big, { yPercent: 50, opacity: 0 }, {
                yPercent: 0, opacity: 1, ease: 'none',
                scrollTrigger: { trigger: '.site-footer', start: 'top bottom', end: 'bottom bottom', scrub: true },
            });
        }

        // چرخش آیکن‌های آمار
        $$('.stat__icon').forEach((icon) => {
            gsap.fromTo(icon, { rotate: -30, scale: .6 }, { rotate: 0, scale: 1, duration: 1, ease: 'back.out(2)', scrollTrigger: { trigger: icon, start: 'top 90%' } });
        });
    }

    /* ---------------------------------------------------------------
       نوار متحرک با سرعت وابسته به اسکرول
    --------------------------------------------------------------- */
    function initMarquee() {
        $$('[data-marquee]').forEach((marquee) => {
            const track = $('.marquee__track', marquee);
            const group = $('.marquee__group', marquee);
            if (!track || !group) return;

            // کپی کافی برای پر کردن عرض صفحه
            const needed = Math.max(2, Math.ceil((window.innerWidth * 2) / Math.max(group.offsetWidth, 1)));
            for (let i = 1; i < needed; i++) track.appendChild(group.cloneNode(true)).setAttribute('aria-hidden', 'true');

            if (!hasGsap || reduced) return;
            const reverse = marquee.hasAttribute('data-marquee-reverse');
            const speed = parseFloat(marquee.dataset.marquee) || 40;
            const distance = group.offsetWidth;
            let dir = reverse ? -1 : 1;

            const tween = gsap.fromTo(track, { x: 0 }, { x: distance, duration: distance / speed, ease: 'none', repeat: -1 });
            tween.totalTime(tween.duration() * 500);
            tween.timeScale(dir);

            if (hasST) {
                ScrollTrigger.create({
                    trigger: marquee, start: 'top bottom', end: 'bottom top',
                    onUpdate(self) {
                        const v = self.getVelocity();
                        const d = self.direction === 1 ? 1 : -1;
                        gsap.to(tween, { timeScale: dir * d * (1 + Math.min(Math.abs(v) / 250, 6)), duration: .2, overwrite: true });
                        gsap.to(tween, { timeScale: dir * d, duration: 1.2, delay: .2 });
                    },
                });
            }
        });
    }

    /* ---------------------------------------------------------------
       اسکرول افقی پروژه‌ها (صفحه اصلی)
    --------------------------------------------------------------- */
    function initHorizontalProjects() {
        const section = $('.projects-h');
        if (!section || !hasST || reduced) return;
        const track = $('.projects-h__track', section);
        const bar = $('.projects-h__progress span', section);

        ScrollTrigger.matchMedia({
            '(min-width: 992px)': () => {
                section.classList.add('is-pinned');
                const dist = () => Math.max(0, track.scrollWidth - window.innerWidth);
                const tween = gsap.to(track, {
                    x: dist, ease: 'none',
                    scrollTrigger: {
                        trigger: section, start: 'top top', end: () => '+=' + dist(), pin: true, scrub: 1, invalidateOnRefresh: true, anticipatePin: 1,
                        onUpdate: (self) => { if (bar) bar.style.transform = `scaleX(${self.progress})`; },
                    },
                });
                $$('.project-card img', track).forEach((img) => {
                    gsap.fromTo(img, { xPercent: -8, scale: 1.15 }, {
                        xPercent: 8, ease: 'none',
                        scrollTrigger: { trigger: img.closest('.project-card'), containerAnimation: tween, start: 'left right', end: 'right left', scrub: true },
                    });
                });
                return () => section.classList.remove('is-pinned');
            },
        });
    }

    /* ---------------------------------------------------------------
       جلوه‌های تعاملی ماوس: مغناطیسی، سه‌بعدی و نورافکن
    --------------------------------------------------------------- */
    function initInteractions() {
        if (isTouch || !hasGsap || reduced) return;

        $$('[data-magnetic]').forEach((el) => {
            const strength = parseFloat(el.dataset.magnetic) || .35;
            el.addEventListener('mousemove', (e) => {
                const r = el.getBoundingClientRect();
                gsap.to(el, { x: (e.clientX - r.left - r.width / 2) * strength, y: (e.clientY - r.top - r.height / 2) * strength, duration: .6, ease: 'power3.out' });
            });
            el.addEventListener('mouseleave', () => gsap.to(el, { x: 0, y: 0, duration: .9, ease: 'elastic.out(1,.4)' }));
        });

        $$('[data-tilt]').forEach((el) => {
            const max = parseFloat(el.dataset.tilt) || 8;
            el.addEventListener('mousemove', (e) => {
                const r = el.getBoundingClientRect();
                const px = (e.clientX - r.left) / r.width;
                const py = (e.clientY - r.top) / r.height;
                el.style.setProperty('--mx', px * 100 + '%');
                el.style.setProperty('--my', py * 100 + '%');
                gsap.to(el, { rotateY: (px - .5) * max * 2, rotateX: (.5 - py) * max * 2, transformPerspective: 900, duration: .6, ease: 'power3.out' });
            });
            el.addEventListener('mouseleave', () => gsap.to(el, { rotateX: 0, rotateY: 0, duration: 1, ease: 'elastic.out(1,.5)' }));
        });
    }

    /* ---------------------------------------------------------------
       اسلایدر نظرات
    --------------------------------------------------------------- */
    function initTestimonials() {
        const el = $('.testimonials-swiper');
        if (!el || typeof window.Swiper === 'undefined') return;
        new Swiper(el, {
            speed: 1000,
            loop: $$('.swiper-slide', el).length > 1,
            grabCursor: true,
            autoplay: { delay: 6000, disableOnInteraction: false },
            effect: 'creative',
            creativeEffect: {
                prev: { shadow: false, translate: ['-20%', 0, -300], rotate: [0, 0, -4], opacity: 0 },
                next: { translate: ['100%', 0, 0], rotate: [0, 0, 6] },
            },
            navigation: { nextEl: '.t-next', prevEl: '.t-prev' },
        });
    }

    /* ---------------------------------------------------------------
       آکاردئون سوالات
    --------------------------------------------------------------- */
    function initAccordion() {
        $$('.accordion').forEach((acc) => {
            const items = $$('.accordion__item', acc);
            const setOpen = (item, open) => {
                const body = $('.accordion__body', item);
                item.classList.toggle('is-open', open);
                $('.accordion__head', item).setAttribute('aria-expanded', open);
                body.style.height = open ? body.scrollHeight + 'px' : '0px';
            };
            items.forEach((item, i) => {
                $('.accordion__head', item).addEventListener('click', () => {
                    const open = !item.classList.contains('is-open');
                    items.forEach((other) => other !== item && setOpen(other, false));
                    setOpen(item, open);
                    if (hasST) setTimeout(() => ScrollTrigger.refresh(), 550);
                });
                if (i === 0) setOpen(item, true);
            });
        });
    }

    /* ---------------------------------------------------------------
       مودال ویدیو و لایت‌باکس گالری
    --------------------------------------------------------------- */
    function initModals() {
        const videoModal = $('#videoModal');
        if (videoModal) {
            const box = $('.modal__box', videoModal);
            const close = () => { videoModal.classList.remove('is-open'); setTimeout(() => { box.innerHTML = ''; }, 400); lenis && lenis.start(); };
            $$('[data-video]').forEach((btn) => btn.addEventListener('click', (e) => {
                e.preventDefault();
                const src = btn.dataset.video;
                if (!src) return;
                box.innerHTML = /\.(mp4|webm)(\?|$)/i.test(src)
                    ? `<video src="${src}" controls autoplay playsinline></video>`
                    : `<iframe src="${src}" allow="autoplay; fullscreen" allowfullscreen></iframe>`;
                videoModal.classList.add('is-open');
                lenis && lenis.stop();
            }));
            videoModal.addEventListener('click', (e) => { if (e.target === videoModal || e.target.closest('.modal__close')) close(); });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && videoModal.classList.contains('is-open')) close(); });
        }

        const lb = $('#lightbox');
        const links = $$('[data-lightbox]');
        if (lb && links.length) {
            const img = $('.lightbox__img', lb);
            let index = 0;
            const show = (i) => {
                index = (i + links.length) % links.length;
                if (hasGsap) gsap.fromTo(img, { opacity: 0, x: 40 }, { opacity: 1, x: 0, duration: .5, ease: 'power3.out' });
                img.src = links[index].href;
            };
            const close = () => { lb.classList.remove('is-open'); lenis && lenis.start(); };
            links.forEach((a, i) => a.addEventListener('click', (e) => { e.preventDefault(); show(i); lb.classList.add('is-open'); lenis && lenis.stop(); }));
            $('.lightbox__next', lb).addEventListener('click', () => show(index + 1));
            $('.lightbox__prev', lb).addEventListener('click', () => show(index - 1));
            lb.addEventListener('click', (e) => { if (e.target === lb || e.target.closest('.modal__close')) close(); });
            document.addEventListener('keydown', (e) => {
                if (!lb.classList.contains('is-open')) return;
                if (e.key === 'Escape') close();
                if (e.key === 'ArrowLeft') show(index + 1);
                if (e.key === 'ArrowRight') show(index - 1);
            });
        }
    }

    /* ---------------------------------------------------------------
       فیلتر پروژه‌ها با انیمیشن Flip
    --------------------------------------------------------------- */
    function initProjectFilter() {
        const bar = $('.filter-bar');
        const grid = $('.projects-grid[data-filterable]');
        if (!bar || !grid) return;
        const cards = $$('.project-card', grid);

        bar.addEventListener('click', (e) => {
            const btn = e.target.closest('.filter-btn');
            if (!btn) return;
            $$('.filter-btn', bar).forEach((b) => b.classList.toggle('is-active', b === btn));
            const filter = btn.dataset.filter;
            const state = window.Flip ? Flip.getState(cards) : null;

            cards.forEach((card) => {
                const show = filter === '*' || card.dataset.category === filter;
                card.classList.toggle('is-hidden', !show);
            });

            if (state) {
                Flip.from(state, {
                    duration: .8, ease: 'power3.inOut', scale: true, absolute: true, stagger: .03,
                    onEnter: (els) => gsap.fromTo(els, { opacity: 0, scale: .8 }, { opacity: 1, scale: 1, duration: .6 }),
                    onLeave: (els) => gsap.to(els, { opacity: 0, scale: .8, duration: .5 }),
                    onComplete: () => hasST && ScrollTrigger.refresh(),
                });
            }
        });
    }

    /* ---------------------------------------------------------------
       بازگشت به بالا و نوار پیشرفت مطالعه
    --------------------------------------------------------------- */
    function initScrollUi() {
        const toTop = $('.to-top');
        const wa = $('.whatsapp-float');
        const circle = toTop && $('circle', toTop);
        const reading = $('.reading-progress');
        const indicator = $('.scroll-indicator span');
        const topRect = toTop && $('rect', toTop);
        const article = $('[data-article]');

        const update = () => {
            const max = document.documentElement.scrollHeight - window.innerHeight;
            const p = max > 0 ? window.scrollY / max : 0;
            if (wa) wa.classList.toggle('is-visible', window.scrollY > 300 || !$('.hero'));
            if (toTop) {
                toTop.classList.toggle('is-visible', window.scrollY > 600);
                if (circle) circle.style.strokeDashoffset = 164 - 164 * p;
                if (topRect) topRect.style.setProperty('--o', 164 - 164 * p);
            }
            if (indicator) indicator.style.transform = `translateY(${p * (window.innerHeight - 90)}px)`;
            if (reading && article) {
                const r = article.getBoundingClientRect();
                const ap = Math.min(1, Math.max(0, -r.top / (r.height - window.innerHeight)));
                reading.style.transform = `scaleX(${ap})`;
            }
        };
        window.addEventListener('scroll', update, { passive: true });
        update();
        if (toTop) toTop.addEventListener('click', () => scrollToY(0));
    }

    /* ---------------------------------------------------------------
       ارسال فرم تماس بدون بارگذاری مجدد
    --------------------------------------------------------------- */
    function initContactForm() {
        const form = $('#contactForm');
        if (!form || !window.fetch) return;
        const alertBox = $('#formAlert');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = $('button[type=submit]', form);
            const label = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>در حال ارسال...</span><i class="ri-loader-4-line"></i>';
            $$('.error', form).forEach((el) => el.remove());

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(form),
                });
                const data = await res.json().catch(() => ({}));
                if (res.ok) {
                    alertBox.className = 'alert alert--success';
                    alertBox.innerHTML = '<i class="ri-checkbox-circle-line"></i> ' + (data.message || 'پیام شما ارسال شد.');
                    form.reset();
                } else if (res.status === 422 && data.errors) {
                    Object.entries(data.errors).forEach(([name, msgs]) => {
                        const input = form.querySelector(`[name="${name}"]`);
                        if (input) input.closest('.field').insertAdjacentHTML('beforeend', `<span class="error">${msgs[0]}</span>`);
                    });
                    alertBox.className = 'alert alert--error';
                    alertBox.innerHTML = '<i class="ri-error-warning-line"></i> لطفا خطاهای فرم را بررسی کنید.';
                } else if (res.status === 429) {
                    alertBox.className = 'alert alert--error';
                    alertBox.innerHTML = '<i class="ri-time-line"></i> تعداد درخواست‌ها زیاد است؛ لطفا کمی بعد تلاش کنید.';
                } else {
                    throw new Error();
                }
            } catch (err) {
                alertBox.className = 'alert alert--error';
                alertBox.innerHTML = '<i class="ri-error-warning-line"></i> خطایی رخ داد، لطفا دوباره تلاش کنید.';
            }
            alertBox.hidden = false;
            if (hasGsap) gsap.fromTo(alertBox, { y: -10, opacity: 0 }, { y: 0, opacity: 1, duration: .5 });
            btn.disabled = false;
            btn.innerHTML = label;
        });
    }

    /* ---------------------------------------------------------------
       اجرا
    --------------------------------------------------------------- */
    initPageTransition();
    initCursor();
    initHeader();
    initHero();
    initBlueprint();
    initGridSpot();
    initShop();
    initMarquee();
    initAccordion();
    initModals();
    initProjectFilter();
    initScrollUi();
    initContactForm();
    initInteractions();
    initTestimonials();

    // انیمیشن‌های اسکرول پس از پیش‌بارگذار
    ready.then(() => {
        initScrollAnimations();
        initHorizontalProjects();
        initTimeline();
        if (hasST) {
            ScrollTrigger.refresh();
            window.addEventListener('load', () => ScrollTrigger.refresh());
        }
        if (hasGsap && !reduced && $('.page-hero')) {
            const title = $('.page-hero h1');
            if (title) gsap.fromTo(splitWords(title), { yPercent: 110 }, { yPercent: 0, duration: 1.2, ease: 'power4.out', stagger: .06 });
            gsap.fromTo('.page-hero .breadcrumb, .page-hero .post-head-meta, .page-hero .eyebrow', { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: .8, delay: .3, stagger: .1 });
        }
        if (hasGsap && !reduced && $('.project-hero')) {
            const title = $('.project-hero h1');
            if (title) gsap.fromTo(splitWords(title), { yPercent: 110 }, { yPercent: 0, duration: 1.3, ease: 'power4.out', stagger: .07 });
            gsap.fromTo('.project-hero__bg img', { scale: 1.25 }, { scale: 1, duration: 2.2, ease: 'power3.out' });
            if (hasST) gsap.to('.project-hero__bg img', { yPercent: 12, ease: 'none', scrollTrigger: { trigger: '.project-hero', start: 'top top', end: 'bottom top', scrub: true } });
        }
    });
})();
