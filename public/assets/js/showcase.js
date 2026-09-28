/* ==========================================================================
   طهورنیان — اجزای نمایشی ویژه
   اسلایدر قبل و بعد، ساختمان در حال ساخت، ماشین‌حساب هزینه، نمای سه‌بعدی،
   نقشه پروژه‌ها، ویدیوی کارت‌ها و عمق سه‌بعدی تصویر هیرو.
   وابسته به app.js (window.TH)، GSAP و در صورت نیاز Three.js (بارگذاری تنبل).
   ========================================================================== */
(function () {
    'use strict';
    const TH = window.TH;
    if (!TH) return;
    const { $, $$, toast, haptic, onSwipe, reduced, isTouch, hasGsap, hasST, faDigits } = TH;

    /* ---------------------------------------------------------------
       ۱. اسلایدر قبل و بعد
    --------------------------------------------------------------- */
    function initBeforeAfter() {
        $$('[data-ba]').forEach((ba) => {
            const handle = $('.ba__handle', ba);
            let pos = 50;
            let dragging = false;
            const set = (p) => {
                pos = Math.max(0, Math.min(100, p));
                ba.style.setProperty('--pos', pos + '%');
                handle.setAttribute('aria-valuenow', Math.round(pos));
                const labels = $$('.ba__label', ba);
                if (labels[0]) labels[0].style.opacity = pos > 88 ? 0 : 1;   // برچسب «قبل» سمت راست
                if (labels[1]) labels[1].style.opacity = pos < 12 ? 0 : 1;   // برچسب «بعد» سمت چپ
            };
            const fromEvent = (x) => {
                const r = ba.getBoundingClientRect();
                return ((x - r.left) / r.width) * 100;
            };
            ba.addEventListener('pointerdown', (e) => {
                if (e.pointerType === 'mouse' && e.button !== 0) return;
                dragging = true;
                ba.classList.add('is-dragging');
                ba.setPointerCapture(e.pointerId);
                if (hasGsap) gsap.killTweensOf(ba);
                set(fromEvent(e.clientX));
                haptic(6);
            });
            ba.addEventListener('pointermove', (e) => { if (dragging) set(fromEvent(e.clientX)); });
            const stop = () => { dragging = false; ba.classList.remove('is-dragging'); };
            ba.addEventListener('pointerup', stop);
            ba.addEventListener('pointercancel', stop);
            handle.addEventListener('keydown', (e) => {
                const step = e.shiftKey ? 10 : 3;
                if (e.key === 'ArrowLeft') { set(pos - step); e.preventDefault(); }
                if (e.key === 'ArrowRight') { set(pos + step); e.preventDefault(); }
                if (e.key === 'Home') set(0);
                if (e.key === 'End') set(100);
            });
            set(50);

            // معرفی: یک بار جارو کردن کامل هنگام دیده شدن
            if (hasST && !reduced) {
                const o = { p: 92 };
                ScrollTrigger.create({
                    trigger: ba, start: 'top 75%', once: true,
                    onEnter: () => gsap.timeline()
                        .fromTo(o, { p: 92 }, { p: 8, duration: 1.4, ease: 'power2.inOut', onUpdate: () => set(o.p) })
                        .to(o, { p: 50, duration: 1, ease: 'power3.out', onUpdate: () => set(o.p) }),
                });
            }
        });
    }

    /* ---------------------------------------------------------------
       ۲. ساختمانی که با اسکرول ساخته می‌شود + هماهنگی با تایم‌لاین
    --------------------------------------------------------------- */
    function initBuilding() {
        const section = $('[data-timeline-section]');
        const svg = section && $('[data-building]', section);
        if (!svg) return;
        const steps = $$('[data-tl]', section);
        const stageNum = $('[data-stage-num]', section);
        const stageTitle = $('[data-stage-title]', section);
        const bar = $('[data-stage-bar]', section);

        // آماده‌سازی خطوط برای رسم تدریجی
        $$('.draw', svg).forEach((el) => {
            const len = el.getTotalLength ? Math.ceil(el.getTotalLength()) : 400;
            el.style.strokeDasharray = el.classList.contains('bld-sketch') ? '6 5' : len;
            el.dataset.len = len;
        });

        if (!hasST || reduced) {
            // بدون انیمیشن، ساختمان تکمیل‌شده نمایش داده می‌شود
            $$('.bld-sketch, .bld-dim, .bld-crane', svg).forEach((el) => { el.style.opacity = 0; });
            $$('.bld-win', svg).forEach((w, i) => w.classList.toggle('is-lit', (i * 7) % 5 < 2));
            if (bar) bar.style.width = '100%';
            const lastStep = steps[steps.length - 1];
            if (lastStep && stageNum) stageNum.textContent = faDigits(steps.length);
            if (lastStep && stageTitle) stageTitle.textContent = $('h3', lastStep).lastChild.textContent.trim();
            return;
        }

        const q = (s) => $$(s, svg);
        const drawIn = (els) => els.map((el) => [el, { strokeDasharray: el.dataset.len, strokeDashoffset: el.dataset.len }, { strokeDashoffset: 0 }]);
        gsap.set(q('.bld-sketch, .bld-dim, .bld-found, .bld-col, .bld-slab, .bld-crane, .bld-floor, .bld-roof, .bld-door, .bld-flag, .bld-tree'), { opacity: 0 });
        gsap.set(q('.bld-found'), { scaleY: 0, transformOrigin: '50% 100%', transformBox: 'fill-box', opacity: 1 });
        gsap.set(q('.bld-floor'), { opacity: 0, y: 10 });

        const tl = gsap.timeline({ defaults: { ease: 'none' } });
        // مرحله ۱: طرح
        tl.to(q('.bld-sketch'), { opacity: 1, duration: .3 }, 0);
        drawIn(q('.bld-sketch')).forEach(([el, f, t]) => tl.fromTo(el, { strokeDasharray: el.dataset.len, strokeDashoffset: el.dataset.len }, { strokeDashoffset: 0, duration: 1 }, 0));
        // مرحله ۲: ابعاد
        tl.to(q('.bld-dim'), { opacity: 1, duration: .3 }, 1);
        q('.bld-dim .draw').forEach((el) => tl.fromTo(el, { strokeDasharray: el.dataset.len, strokeDashoffset: el.dataset.len }, { strokeDashoffset: 0, duration: .8 }, 1));
        tl.to(q('.bld-tree'), { opacity: 1, duration: .6 }, 1.2);
        // مرحله ۳: فونداسیون
        tl.to(q('.bld-found'), { scaleY: 1, duration: .8 }, 2);
        tl.to(q('.bld-sketch'), { opacity: .25, duration: .5 }, 2.2);
        // مرحله ۴: اسکلت، سقف‌ها و جرثقیل
        tl.to(q('.bld-crane'), { opacity: 1, duration: .2 }, 3);
        q('.bld-crane .draw').forEach((el) => tl.fromTo(el, { strokeDasharray: el.dataset.len, strokeDashoffset: el.dataset.len }, { strokeDashoffset: 0, duration: .5 }, 3));
        tl.to(q('.bld-col'), { opacity: 1, duration: .1 }, 3.1);
        q('.bld-col').forEach((el) => tl.fromTo(el, { strokeDasharray: el.dataset.len, strokeDashoffset: el.dataset.len }, { strokeDashoffset: 0, duration: .9 }, 3.1));
        q('.bld-slab').forEach((el, i) => {
            tl.to(el, { opacity: 1, duration: .05 }, 3.2 + i * .12);
            tl.fromTo(el, { strokeDasharray: el.dataset.len, strokeDashoffset: el.dataset.len }, { strokeDashoffset: 0, duration: .3 }, 3.2 + i * .12);
        });
        tl.to(q('.bld-hook'), { attr: { y2: 400 }, duration: .9, yoyo: true, repeat: 1 }, 3.1);
        // مرحله ۵: نما، پنجره‌ها، سقف، تحویل
        q('.bld-floor').forEach((el, i) => tl.to(el, { opacity: 1, y: 0, duration: .25 }, 4.1 + i * .08));
        tl.to(q('.bld-roof, .bld-door'), { opacity: 1, duration: .3 }, 4.7);
        tl.to(q('.bld-crane'), { opacity: 0, duration: .4 }, 4.8);
        tl.to(q('.bld-dim, .bld-sketch'), { opacity: 0, duration: .4 }, 4.8);
        tl.to(q('.bld-flag'), { opacity: 1, duration: .2 }, 4.95);
        tl.call(() => {}, null, 5);

        // پنجره‌های روشن به صورت تصادفی در انتهای ساخت
        const wins = q('.bld-win');
        const light = (on) => wins.forEach((w, i) => w.classList.toggle('is-lit', on && (i * 7) % 5 < 2));

        const total = steps.length || 5;
        let last = -1;
        ScrollTrigger.create({
            trigger: $('.timeline', section),
            start: 'top 65%',
            end: 'bottom 55%',
            scrub: .6,
            animation: tl,
            onUpdate: (self) => {
                const p = self.progress;
                if (bar) bar.style.width = (p * 100) + '%';
                light(p > .97);
                const idx = Math.min(total - 1, Math.floor(p * total * .999));
                if (idx !== last && steps.length) {
                    last = idx;
                    if (!isTouch || window.innerWidth < 992) steps.forEach((s, i) => s.classList.toggle('is-open', i === idx));
                    if (stageNum) stageNum.textContent = faDigits(idx + 1);
                    if (stageTitle) stageTitle.textContent = $('h3', steps[idx]).lastChild.textContent.trim();
                    if (hasGsap && stageTitle) gsap.fromTo(stageTitle, { y: 10, opacity: 0 }, { y: 0, opacity: 1, duration: .4 });
                }
            },
        });
    }

    /* ---------------------------------------------------------------
       ۳. ماشین‌حساب هزینه ساخت
    --------------------------------------------------------------- */
    const toWords = (n) => {
        if (!n) return '';
        const parts = [];
        [[1e12, 'هزار میلیارد'], [1e9, 'میلیارد'], [1e6, 'میلیون']].forEach(([v, name]) => {
            if (n >= v) { parts.push(faDigits(Math.floor(n / v)) + ' ' + name); n %= v; }
        });
        return parts.length ? 'حدود ' + parts.join(' و ') + ' تومان' : '';
    };
    const fmt = (n) => faDigits(Math.round(n).toLocaleString('en-US')).replace(/,/g, '٬');

    function initCalculator() {
        const calc = $('[data-calc]');
        if (!calc) return;
        const form = $('form', calc);
        const d = calc.dataset;
        const price = { economy: +d.economy, standard: +d.standard, luxury: +d.luxury };
        const el = (s) => $(s, calc);
        const totalEl = el('[data-total]');
        const qualityName = { economy: 'اقتصادی', standard: 'استاندارد', luxury: 'لوکس' };
        let shown = 0;
        let current = {};

        const compute = () => {
            const f = new FormData(form);
            const area = +f.get('area');
            const floors = +f.get('floors');
            const quality = f.get('quality');
            const steel = f.get('structure') === 'steel';
            const basement = !!f.get('basement');
            let perM = price[quality] || price.standard;
            if (steel) perM *= (+d.steel || 1.08);
            if (floors > 4) perM *= 1 + ((floors - 4) * (+d.floor || 1.5)) / 100;
            if (basement) perM *= 1 + (+d.basement || 9) / 100;
            const total = perM * area;
            const months = Math.max(4, Math.round(6 + floors * 1.6 + area / 900));
            current = { area, floors, quality, steel, basement, perM, total, months };
            return current;
        };

        const render = () => {
            const r = compute();
            $$('input[type=range]', form).forEach((inp) => {
                inp.style.setProperty('--fill', ((inp.value - inp.min) / (inp.max - inp.min)) * 100 + '%');
                const out = $(`[data-out="${inp.name}"]`, form);
                if (out) out.textContent = fmt(inp.value);
            });
            el('[data-per-m]').textContent = fmt(r.perM) + ' تومان';
            el('[data-duration]').textContent = faDigits(r.months) + ' ماه';
            el('[data-range]').textContent = fmt(r.total * .9) + ' تا ' + fmt(r.total * 1.12) + ' تومان';
            el('[data-words]').textContent = toWords(r.total);
            const o = { v: shown };
            if (hasGsap && !reduced) {
                gsap.to(o, { v: r.total, duration: .9, ease: 'power3.out', overwrite: true, onUpdate: () => { totalEl.textContent = fmt(o.v); shown = o.v; } });
            } else {
                totalEl.textContent = fmt(r.total); shown = r.total;
            }
        };
        form.addEventListener('input', render);
        form.addEventListener('change', (e) => { if (e.target.type === 'radio' || e.target.type === 'checkbox') haptic(6); });
        render();

        // فرم درخواست برآورد دقیق
        const modal = $('[data-calc-modal]');
        const cform = $('[data-calc-form]', modal);
        const alertBox = $('[data-calc-alert]', modal);
        const openModal = () => {
            const r = current;
            const rows = [
                ['زیربنا', fmt(r.area) + ' متر'], ['طبقات', faDigits(r.floors)],
                ['اسکلت', r.steel ? 'فلزی' : 'بتنی'], ['کیفیت', qualityName[r.quality]],
                ['زیرزمین', r.basement ? 'دارد' : 'ندارد'], ['برآورد', toWords(r.total).replace('حدود ', '')],
            ];
            $('[data-summary]', modal).innerHTML = rows.map(([k, v]) => `<div>${k}<b>${v}</b></div>`).join('');
            $('[data-body]', cform).value = 'درخواست برآورد هزینه از ماشین‌حساب سایت:\n' + rows.map(([k, v]) => `${k}: ${v}`).join('\n') + `\nهزینه تخمینی: ${fmt(r.total)} تومان`;
            alertBox.hidden = true;
            modal.classList.add('is-open');
            TH.lenis && TH.lenis.stop();
            setTimeout(() => $('input[name=name]', cform).focus(), 300);
        };
        const closeModal = () => { modal.classList.remove('is-open'); TH.lenis && TH.lenis.start(); };
        el('[data-calc-open]').addEventListener('click', openModal);
        modal.addEventListener('click', (e) => { if (e.target === modal || e.target.closest('[data-close]')) closeModal(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal(); });
        onSwipe(modal, { onEnd: (dx, dy, axis) => { if (axis === 'y' && dy > 110) closeModal(); } });
        cform.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = $('button[type=submit]', cform);
            btn.disabled = true;
            try {
                const res = await fetch(cform.action, { method: 'POST', headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: new FormData(cform) });
                const data = await res.json().catch(() => ({}));
                if (res.ok) {
                    haptic([10, 40, 18]);
                    closeModal();
                    cform.reset();
                    toast('درخواست شما ثبت شد؛ به‌زودی با شما تماس می‌گیریم.');
                } else {
                    alertBox.hidden = false;
                    alertBox.className = 'alert alert--error';
                    alertBox.textContent = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'خطایی رخ داد.');
                }
            } catch (err) {
                alertBox.hidden = false; alertBox.className = 'alert alert--error'; alertBox.textContent = 'خطا در ارتباط؛ دوباره تلاش کنید.';
            }
            btn.disabled = false;
        });
    }

    /* ---------------------------------------------------------------
       ۴. نمای سه‌بعدی برج (Three.js، بارگذاری تنبل)
    --------------------------------------------------------------- */
    function loadThree() {
        if (window.THREE) return Promise.resolve();
        return new Promise((resolve, reject) => {
            const s = document.createElement('script');
            s.src = '/assets/vendor/three/three.min.js';
            s.onload = resolve; s.onerror = reject;
            document.head.appendChild(s);
        });
    }

    function webglOk() {
        try { const c = document.createElement('canvas'); return !!(window.WebGLRenderingContext && (c.getContext('webgl2') || c.getContext('webgl'))); } catch (e) { return false; }
    }

    function initTower() {
        const stage = $('[data-tower]');
        if (!stage || !webglOk()) return;
        const io = new IntersectionObserver(([entry]) => {
            if (!entry.isIntersecting) return;
            io.disconnect();
            loadThree().then(() => buildTower(stage)).catch((e) => console.warn('3D', e));
        }, { rootMargin: '400px' });
        io.observe(stage);
    }

    function buildTower(stage) {
        const T = window.THREE;
        const floors = Math.max(4, Math.min(24, +stage.dataset.floors || 12));
        const renderer = new T.WebGLRenderer({ antialias: true, alpha: true, powerPreference: 'low-power' });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
        renderer.outputColorSpace = T.SRGBColorSpace;
        stage.prepend(renderer.domElement);

        const scene = new T.Scene();
        scene.fog = new T.Fog(0x0e1e21, 28, 60);
        const camera = new T.PerspectiveCamera(38, 1, .1, 200);

        const hemi = new T.HemisphereLight(0xdff5e1, 0x0e1e21, 1.1);
        scene.add(hemi);
        const sun = new T.DirectionalLight(0xffe2a8, 2.2);
        sun.position.set(-12, 20, 10);
        scene.add(sun);

        // زمین و شبکه
        const ground = new T.Mesh(new T.CircleGeometry(16, 64), new T.MeshStandardMaterial({ color: 0x13292d, roughness: 1 }));
        ground.rotation.x = -Math.PI / 2;
        scene.add(ground);
        const grid = new T.GridHelper(32, 32, 0x2a4d52, 0x1c3a3f);
        grid.position.y = .01;
        scene.add(grid);

        // برج با طبقات چرخان (پیچش ملایم)
        const tower = new T.Group();
        scene.add(tower);
        const fh = .62;
        const glassDay = new T.MeshStandardMaterial({ color: 0x9ed2e4, metalness: .6, roughness: .15, transparent: true, opacity: .88 });
        const glassBase = glassDay.color.clone();
        const slabMat = new T.MeshStandardMaterial({ color: 0xe9ecec, roughness: .6 });
        const edgeMat = new T.LineBasicMaterial({ color: 0xfbb12e, transparent: true, opacity: .75 });
        const floorsArr = [];
        const lights = [];
        // بافت پنجره‌های روشن (چند الگوی تصادفی)
        const winTex = [0, 1, 2].map((k) => {
            const c = document.createElement('canvas');
            c.width = 128; c.height = 16;
            const x = c.getContext('2d');
            for (let j = 0; j < 12; j++) {
                const on = ((j * 7 + k * 5) % 11) < 6;
                x.fillStyle = on ? 'rgba(255,199,102,1)' : 'rgba(255,199,102,.12)';
                x.fillRect(j * 10.6 + 1.5, 2, 7.6, 12);
            }
            const t = new T.CanvasTexture(c);
            t.colorSpace = T.SRGBColorSpace;
            return t;
        });
        for (let i = 0; i < floors; i++) {
            const g = new T.Group();
            const w = 3.2 - Math.sin((i / floors) * Math.PI) * .25;
            const glass = new T.Mesh(new T.BoxGeometry(w, fh * .8, w), glassDay);
            glass.position.y = fh * .45;
            const slab = new T.Mesh(new T.BoxGeometry(w + .35, fh * .12, w + .35), slabMat);
            const edges = new T.LineSegments(new T.EdgesGeometry(glass.geometry), edgeMat);
            edges.position.copy(glass.position);
            // پنجره‌های روشن برای حالت شب
            const lit = new T.Mesh(new T.BoxGeometry(w * 1.012, fh * .5, w * 1.012), new T.MeshBasicMaterial({ map: winTex[i % 3], transparent: true, opacity: 0, depthWrite: false }));
            lit.position.y = fh * .45;
            lights.push(lit);
            g.add(slab, glass, edges, lit);
            g.position.y = i * fh;
            g.rotation.y = (i / floors) * .9;
            tower.add(g);
            floorsArr.push(g);
        }
        const roof = new T.Mesh(new T.BoxGeometry(2.2, .3, 2.2), slabMat);
        roof.position.y = floors * fh + .1;
        roof.rotation.y = .9;
        tower.add(roof);

        // ساختمان‌های اطراف
        const cityMat = new T.MeshStandardMaterial({ color: 0x1c3a3f, roughness: .9 });
        [[-7, 3, 2.2, 3.5], [6.5, -2, 2, 5], [-5, -6, 2.6, 2.4], [7, 5, 1.8, 3], [0, 8, 3, 1.6], [-8, -1, 1.6, 6]].forEach(([x, z, s, h]) => {
            const b = new T.Mesh(new T.BoxGeometry(s, h, s), cityMat);
            b.position.set(x, h / 2, z);
            scene.add(b);
        });

        // درختان کم‌حجم
        const treeMat = new T.MeshStandardMaterial({ color: 0x4f9a53, roughness: .8 });
        for (let i = 0; i < 14; i++) {
            const a = (i / 14) * Math.PI * 2;
            const t = new T.Mesh(new T.SphereGeometry(.45 + Math.random() * .25, 10, 8), treeMat);
            t.position.set(Math.cos(a) * 4.2, .45, Math.sin(a) * 4.2);
            scene.add(t);
        }

        const height = floors * fh;
        const target = new T.Vector3(0, height * .47, 0);
        let radius = Math.max(13, height * 2.05);
        let theta = .7, phi = 1.22;
        let vTheta = 0;
        let auto = true;

        const place = () => {
            camera.position.set(target.x + radius * Math.sin(phi) * Math.cos(theta), target.y + radius * Math.cos(phi), target.z + radius * Math.sin(phi) * Math.sin(theta));
            camera.lookAt(target);
        };
        const resize = () => {
            const w = stage.clientWidth, h = stage.clientHeight;
            renderer.setSize(w, h, false);
            camera.aspect = w / h;
            camera.updateProjectionMatrix();
        };
        resize();
        window.addEventListener('resize', resize);

        // کشیدن برای چرخش (ماوس و لمس)
        let dragging = false, lx = 0, ly = 0;
        stage.addEventListener('pointerdown', (e) => {
            if (e.target.closest('button')) return;
            dragging = true; auto = false; lx = e.clientX; ly = e.clientY;
            stage.setPointerCapture(e.pointerId);
            stage.classList.add('was-dragged');
        });
        stage.addEventListener('pointermove', (e) => {
            if (!dragging) return;
            const dx = e.clientX - lx, dy = e.clientY - ly;
            lx = e.clientX; ly = e.clientY;
            vTheta = dx * .006;
            theta += vTheta;
            phi = Math.max(.55, Math.min(1.45, phi - dy * .004));
        });
        const up = () => { dragging = false; setTimeout(() => { auto = true; }, 2500); };
        stage.addEventListener('pointerup', up);
        stage.addEventListener('pointercancel', up);
        // چرخش گوشی (اندروید)
        window.addEventListener('deviceorientation', (e) => {
            if (dragging || e.gamma == null || !isTouch) return;
            target.x = Math.max(-1, Math.min(1, e.gamma / 45)) * .8;
        });

        // حالت شب / روز
        let night = false;
        const toggle = $('[data-tower-mode]', stage);
        toggle.addEventListener('click', () => {
            night = !night;
            haptic(8);
            $('i', toggle).className = night ? 'ri-sun-line' : 'ri-moon-line';
            const to = night ? { hemi: .25, sun: .2, lit: .95 } : { hemi: 1.1, sun: 2.2, lit: 0 };
            if (hasGsap) {
                gsap.to(hemi, { intensity: to.hemi, duration: 1 });
                gsap.to(sun, { intensity: to.sun, duration: 1 });
                gsap.to(glassDay.color, night ? { r: .01, g: .03, b: .045, duration: 1 } : { r: glassBase.r, g: glassBase.g, b: glassBase.b, duration: 1 });
                gsap.to(stage, { backgroundColor: night ? '#081214' : 'rgba(0,0,0,0)', duration: 1 });
                lights.forEach((l, i) => gsap.to(l.material, { opacity: to.lit, duration: .8, delay: i * .04 }));
            }
        });

        // انیمیشن ساخت طبقه به طبقه هنگام دیده شدن
        floorsArr.forEach((f) => { f.visible = false; });
        roof.visible = false;
        const buildUp = () => {
            floorsArr.forEach((f, i) => {
                setTimeout(() => {
                    f.visible = true;
                    const y = f.position.y;
                    if (hasGsap && !reduced) {
                        f.position.y = y + 2.5;
                        f.scale.set(.6, .6, .6);
                        gsap.to(f.position, { y, duration: .7, ease: 'bounce.out' });
                        gsap.to(f.scale, { x: 1, y: 1, z: 1, duration: .5, ease: 'back.out(2)' });
                    }
                }, reduced ? 0 : i * 110);
            });
            setTimeout(() => { roof.visible = true; }, reduced ? 0 : floors * 110 + 300);
        };

        let visible = false;
        let built = false;
        new IntersectionObserver(([en]) => {
            visible = en.isIntersecting;
            if (visible && !built) { built = true; buildUp(); }
        }, { threshold: .25 }).observe(stage);

        const tick = () => {
            requestAnimationFrame(tick);
            if (!visible) return;
            if (auto && !reduced) theta += .0035;
            else if (!dragging) { theta += vTheta; vTheta *= .92; }
            place();
            renderer.render(scene, camera);
        };
        place();
        stage.classList.add('is-3d');
        tick();
    }

    /* ---------------------------------------------------------------
       عمق سه‌بعدی تصویر هیرو (با حرکت ماوس یا چرخش گوشی)
    --------------------------------------------------------------- */
    function initHeroDepth() {
        if (!hasGsap || reduced) return;
        const hero = $('.hero');
        if (!hero) return;
        const layers = () => {
            const slide = $('.swiper-slide-active', hero) || hero;
            return [
                [$('.hero-arch', slide), 14], [$('.hero-diamond', slide), -22], [$('.hero-ring--1', slide), 30],
                [$('.hero-ring--2', slide), -34], [$('.hero-leaf--r', slide), 40], [$('.hero-leaf--l', slide), 48],
            ].filter(([el]) => el);
        };
        const move = (nx, ny) => layers().forEach(([el, d]) => gsap.to(el, { x: nx * d, y: ny * d * .6, rotateY: nx * (d > 0 ? 4 : 0), duration: 1, ease: 'power3.out', overwrite: 'auto' }));
        if (!isTouch) {
            hero.addEventListener('mousemove', (e) => {
                const r = hero.getBoundingClientRect();
                move((e.clientX - r.left) / r.width - .5, (e.clientY - r.top) / r.height - .5);
            });
            hero.addEventListener('mouseleave', () => move(0, 0));
        } else {
            window.addEventListener('deviceorientation', (e) => {
                if (e.gamma == null || window.scrollY > window.innerHeight) return;
                move(Math.max(-1, Math.min(1, e.gamma / 30)) * .5, Math.max(-1, Math.min(1, (e.beta - 45) / 30)) * .5);
            });
        }
    }

    /* ---------------------------------------------------------------
       ۹. نقشه پروژه‌ها
    --------------------------------------------------------------- */
    function initMap() {
        const map = $('[data-pmap]');
        if (!map) return;
        const dots = $$('.pmap__dot', map);
        const cards = $$('.pmap__card', map);
        const hint = $('[data-hint]', map);
        const select = (id) => {
            haptic(8);
            dots.forEach((d) => d.classList.toggle('is-active', d.dataset.project === id));
            // نمایش همه پروژه‌های نزدیک به نقطه انتخاب شده
            const sel = dots.find((d) => d.dataset.project === id);
            const pos = (d) => d.transform.baseVal.consolidate().matrix;
            const m = pos(sel);
            const near = dots.filter((d) => { const n = pos(d); return Math.hypot(n.e - m.e, n.f - m.f) < 28; }).map((d) => d.dataset.project);
            let shown = 0;
            cards.forEach((c) => {
                const on = near.includes(c.dataset.card) && shown < 3;
                if (on) shown++;
                c.hidden = !on;
                if (on && hasGsap) gsap.fromTo(c, { x: 30, opacity: 0 }, { x: 0, opacity: 1, duration: .5, delay: shown * .06, ease: 'power3.out' });
            });
            if (hint) hint.hidden = true;
        };
        dots.forEach((d) => {
            d.addEventListener('click', () => select(d.dataset.project));
            d.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); select(d.dataset.project); } });
        });
        if (hasST && !reduced) {
            const outline = $('.pmap__outline', map);
            const len = outline.getTotalLength();
            gsap.fromTo(outline, { strokeDasharray: len, strokeDashoffset: len }, { strokeDashoffset: 0, duration: 2.4, ease: 'power2.inOut', scrollTrigger: { trigger: map, start: 'top 80%' } });
            gsap.fromTo(dots, { scale: 0, transformOrigin: 'center', transformBox: 'fill-box' }, { scale: 1, duration: .6, stagger: .08, ease: 'back.out(3)', delay: 1.2, scrollTrigger: { trigger: map, start: 'top 80%' } });
        }
    }

    /* ---------------------------------------------------------------
       ۱۰. ویدیوی کوتاه روی کارت پروژه
    --------------------------------------------------------------- */
    function initCardVideos() {
        const vids = $$('.card-video');
        if (!vids.length) return;
        const saveData = navigator.connection && (navigator.connection.saveData || /2g/.test(navigator.connection.effectiveType || ''));
        const play = (v) => {
            if (!v.src) v.src = v.dataset.src;
            const p = v.play();
            if (p && p.then) p.then(() => v.classList.add('is-playing')).catch(() => {});
            else v.classList.add('is-playing');
        };
        const pause = (v) => { v.classList.remove('is-playing'); v.pause(); };
        if (!isTouch) {
            vids.forEach((v) => {
                const card = v.closest('.project-card');
                card.addEventListener('mouseenter', () => play(v));
                card.addEventListener('mouseleave', () => pause(v));
            });
        } else if (!saveData && !reduced) {
            // موبایل: پخش وقتی کارت وسط صفحه است
            const io = new IntersectionObserver((entries) => entries.forEach((en) => (en.isIntersecting ? play(en.target) : pause(en.target))), { rootMargin: '-35% 0px -35% 0px' });
            vids.forEach((v) => io.observe(v));
        }
    }

    /* ---------------------------------------------------------------
       ۱۲. بستن بنر نصب با کشیدن
    --------------------------------------------------------------- */
    function initBannerSwipe() {
        const banner = $('#installBanner');
        if (!banner) return;
        onSwipe(banner, {
            onMove: (dx, dy, axis) => { if (hasGsap && axis === 'y' && dy > 0) gsap.set(banner, { y: dy }); },
            onEnd: (dx, dy, axis) => {
                if (axis === 'y' && dy > 50) { $('#installClose').click(); if (hasGsap) gsap.set(banner, { clearProps: 'transform' }); }
                else if (hasGsap) gsap.to(banner, { y: 0, duration: .3, clearProps: 'transform' });
            },
        });
    }

    initBeforeAfter();
    initCalculator();
    initMap();
    initCardVideos();
    initBannerSwipe();
    initTower();
    TH.ready.then(() => {
        initBuilding();
        initHeroDepth();
        if (hasST) ScrollTrigger.refresh();
    });
})();
