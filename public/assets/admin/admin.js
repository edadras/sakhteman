/* پنل مدیریت — تعاملات */
(function () {
    'use strict';
    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));
    const root = document.documentElement;

    /* حالت تیره / روشن */
    const themeBtn = $('#themeToggle');
    if (themeBtn) {
        const sync = () => { themeBtn.innerHTML = root.dataset.theme === 'dark' ? '<i class="ri-sun-line"></i>' : '<i class="ri-moon-line"></i>'; };
        sync();
        themeBtn.addEventListener('click', () => {
            root.dataset.theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
            try { localStorage.setItem('admin-theme', root.dataset.theme); } catch (e) {}
            sync();
        });
    }

    /* منوی موبایل */
    const burger = $('#aBurger');
    const overlay = $('.overlay');
    if (burger) burger.addEventListener('click', () => document.body.classList.toggle('sidebar-open'));
    if (overlay) overlay.addEventListener('click', () => document.body.classList.remove('sidebar-open'));

    /* منوی کاربر */
    const user = $('.a-user');
    if (user) {
        $('.a-user__btn', user).addEventListener('click', (e) => { e.stopPropagation(); user.classList.toggle('open'); });
        document.addEventListener('click', () => user.classList.remove('open'));
    }

    /* اعلان‌ها */
    $$('.toast').forEach((toast, i) => {
        setTimeout(() => { toast.classList.add('hide'); setTimeout(() => toast.remove(), 450); }, 4500 + i * 400);
        toast.addEventListener('click', () => toast.remove());
    });

    /* تایید حذف */
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (!form.matches('[data-confirm]') || form.dataset.confirmed) return;
        e.preventDefault();
        const message = form.dataset.confirm || 'آیا از حذف این مورد اطمینان دارید؟';
        const go = () => { form.dataset.confirmed = '1'; form.submit(); };
        if (window.Swal) {
            Swal.fire({
                title: 'تایید حذف',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'بله، حذف شود',
                cancelButtonText: 'انصراف',
                confirmButtonColor: '#ef4444',
                reverseButtons: true,
            }).then((r) => r.isConfirmed && go());
        } else if (confirm(message)) {
            go();
        }
    });

    /* انتخاب همه برای حذف گروهی */
    const checkAll = $('#checkAll');
    const bulkBar = $('#bulkBar');
    const updateBulk = () => {
        const n = $$('.row-check:checked').length;
        if (bulkBar) {
            bulkBar.hidden = n === 0;
            const c = $('.bulk-count', bulkBar);
            if (c) c.textContent = n.toLocaleString('fa-IR');
        }
    };
    if (checkAll) checkAll.addEventListener('change', () => { $$('.row-check').forEach((c) => { c.checked = checkAll.checked; }); updateBulk(); });
    $$('.row-check').forEach((c) => c.addEventListener('change', updateBulk));
    const bulkForm = $('#bulkForm');
    if (bulkForm) {
        bulkForm.addEventListener('submit', () => {
            $$('input[name="ids[]"]', bulkForm).forEach((i) => i.remove());
            $$('.row-check:checked').forEach((c) => {
                const input = document.createElement('input');
                input.type = 'hidden'; input.name = 'ids[]'; input.value = c.value;
                bulkForm.appendChild(input);
            });
        });
    }

    /* تغییر وضعیت سریع (بدون بارگذاری مجدد) */
    $$('form.toggle-form').forEach((form) => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = $('button', form);
            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(form),
                });
                if (!res.ok) throw new Error();
                const data = await res.json();
                btn.className = 'badge toggle-badge ' + (data.value ? 'badge-success' : 'badge-muted');
                btn.innerHTML = data.value ? '<i class="ri-checkbox-circle-fill"></i> فعال' : '<i class="ri-close-circle-line"></i> غیرفعال';
            } catch (err) {
                form.submit();
            }
        });
    });

    /* پیش‌نمایش آپلود تصویر + کشیدن و رها کردن */
    $$('.upload').forEach((box) => {
        const input = $('input[type=file]', box);
        const preview = $('.upload__preview', box);
        const name = $('.upload__body small', box);
        if (!input) return;
        ['dragenter', 'dragover'].forEach((ev) => box.addEventListener(ev, () => box.classList.add('dragover')));
        ['dragleave', 'drop'].forEach((ev) => box.addEventListener(ev, () => box.classList.remove('dragover')));
        input.addEventListener('change', () => {
            const files = Array.from(input.files || []);
            if (!files.length) return;
            if (name) name.textContent = files.length > 1 ? files.length.toLocaleString('fa-IR') + ' فایل انتخاب شد' : files[0].name;
            if (preview && files[0].type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (ev) => { preview.innerHTML = `<img src="${ev.target.result}" alt="">`; };
                reader.readAsDataURL(files[0]);
            }
        });
    });

    /* پیش‌نمایش آیکن */
    $$('[data-icon-input]').forEach((input) => {
        const preview = input.parentElement.querySelector('.icon-preview i');
        input.addEventListener('input', () => { if (preview) preview.className = input.value.trim() || 'ri-question-line'; });
    });

    /* ساخت خودکار نامک */
    const slugInput = $('input[name="slug"]');
    const slugSource = slugInput && $('[data-slug-source]');
    if (slugInput && slugSource) {
        let touched = slugInput.value.length > 0;
        slugInput.addEventListener('input', () => { touched = slugInput.value.length > 0; });
        slugSource.addEventListener('input', () => {
            if (touched) return;
            slugInput.placeholder = slugSource.value.trim().replace(/[‌\s]+/g, '-').replace(/[^\p{L}\p{N}-]+/gu, '').toLowerCase();
        });
    }

    /* ویرایشگر متن (Quill) */
    if (window.Quill) {
        $$('[data-editor]').forEach((textarea) => {
            const holder = document.createElement('div');
            textarea.style.display = 'none';
            textarea.insertAdjacentElement('afterend', holder);
            const q = new Quill(holder, {
                theme: 'snow',
                placeholder: 'متن خود را اینجا بنویسید...',
                modules: {
                    toolbar: [
                        [{ header: [2, 3, 4, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ color: [] }, { background: [] }],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        [{ align: [] }, { direction: 'rtl' }],
                        ['blockquote', 'link', 'image', 'video'],
                        ['clean'],
                    ],
                },
            });
            q.root.innerHTML = textarea.value;
            const form = textarea.closest('form');
            if (form) form.addEventListener('submit', () => {
                textarea.value = q.root.innerHTML === '<p><br></p>' ? '' : q.root.innerHTML;
            });
        });
    }

    /* تب‌ها */
    $$('[data-tabs]').forEach((wrap) => {
        const buttons = $$('.tabs button', wrap);
        const panels = $$('.tab-panel', wrap);
        const hidden = $('input[name="_tab"]', wrap);
        const activate = (id) => {
            buttons.forEach((b) => b.classList.toggle('active', b.dataset.tab === id));
            panels.forEach((p) => p.classList.toggle('active', p.id === 'tab-' + id));
            if (hidden) hidden.value = id;
            history.replaceState(null, '', '#' + id);
        };
        buttons.forEach((b) => b.addEventListener('click', () => activate(b.dataset.tab)));
        const initial = location.hash.slice(1);
        activate(buttons.some((b) => b.dataset.tab === initial) ? initial : buttons[0].dataset.tab);
    });

    /* جلوگیری از ارسال دوباره فرم */
    $$('form[data-once]').forEach((form) => form.addEventListener('submit', () => {
        // با تاخیر غیرفعال می‌شود تا مقدار دکمه ارسال‌کننده (_after) در داده‌های فرم باقی بماند
        setTimeout(() => $$('button[type=submit]', form).forEach((b) => { b.disabled = true; b.style.opacity = .7; }), 0);
        setTimeout(() => $$('button[type=submit]', form).forEach((b) => { b.disabled = false; b.style.opacity = 1; }), 8000);
    }));

    /* هشدار تغییرات ذخیره نشده */
    const dirtyForm = $('form[data-dirty-check]');
    if (dirtyForm) {
        let dirty = false;
        dirtyForm.addEventListener('input', () => { dirty = true; });
        dirtyForm.addEventListener('change', () => { dirty = true; });
        dirtyForm.addEventListener('submit', () => { dirty = false; });
        window.addEventListener('beforeunload', (e) => { if (dirty) { e.preventDefault(); e.returnValue = ''; } });
    }
})();
