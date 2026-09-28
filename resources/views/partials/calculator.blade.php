@if (setting('calc_enabled', '1') === '1')
@php
    $num = fn ($k, $d) => (float) en_num(str_replace([',', '٬'], '', (string) setting($k, $d)));
@endphp
<section class="section calc-section" id="calculator">
    <div class="container">
        @include('partials.sec-head', ['title' => 'برآورد هزینه ساخت', 'en' => 'Cost estimator', 'icon' => 'ri-calculator-line', 'desc' => 'در چند ثانیه هزینه تقریبی ساخت پروژه خود را محاسبه کنید.'])
        <div class="calc" data-calc
             data-economy="{{ $num('calc_price_economy', 18000000) }}"
             data-standard="{{ $num('calc_price_standard', 26000000) }}"
             data-luxury="{{ $num('calc_price_luxury', 42000000) }}"
             data-steel="{{ $num('calc_steel_factor', 1.08) }}"
             data-floor="{{ $num('calc_floor_factor', 1.5) }}"
             data-basement="{{ $num('calc_basement_factor', 9) }}">
            <form class="calc__form" data-reveal="right" onsubmit="return false">
                <div class="calc__field">
                    <label for="calcArea">زیربنای کل <output data-out="area">۵۰۰</output> متر مربع</label>
                    <input type="range" id="calcArea" name="area" min="50" max="10000" step="10" value="500">
                </div>
                <div class="calc__field">
                    <label for="calcFloors">تعداد طبقات <output data-out="floors">۴</output> طبقه</label>
                    <input type="range" id="calcFloors" name="floors" min="1" max="30" step="1" value="4">
                </div>
                <div class="calc__field">
                    <span class="calc__label">نوع اسکلت</span>
                    <div class="seg">
                        <label><input type="radio" name="structure" value="concrete" checked><span><i class="ri-building-2-line"></i>بتنی</span></label>
                        <label><input type="radio" name="structure" value="steel"><span><i class="ri-building-4-line"></i>فلزی</span></label>
                    </div>
                </div>
                <div class="calc__field">
                    <span class="calc__label">کیفیت مصالح و نازک‌کاری</span>
                    <div class="seg seg--3">
                        <label><input type="radio" name="quality" value="economy"><span>اقتصادی</span></label>
                        <label><input type="radio" name="quality" value="standard" checked><span>استاندارد</span></label>
                        <label><input type="radio" name="quality" value="luxury"><span>لوکس</span></label>
                    </div>
                </div>
                <label class="switch-row"><input type="checkbox" name="basement"><span class="switch-row__track"></span>زیرزمین / پارکینگ طبقاتی</label>
            </form>

            <div class="calc__result grid-bg" data-grid-spot data-reveal="left">
                <span class="calc__caption">هزینه تقریبی ساخت</span>
                <div class="calc__total"><span data-total>۰</span><small>تومان</small></div>
                <div class="calc__words" data-words></div>
                <div class="calc__range">بازه تخمینی: <span data-range></span></div>
                <ul class="calc__breakdown">
                    <li><span>هزینه هر متر مربع</span><b data-per-m></b></li>
                    <li><span>مدت زمان تقریبی اجرا</span><b data-duration></b></li>
                </ul>
                <button type="button" class="btn btn--block" data-calc-open>دریافت برآورد دقیق و رایگان <i class="ri-arrow-left-line"></i></button>
                <p class="calc__note">{{ setting('calc_note') }}</p>
            </div>
        </div>

        <div class="modal calc-modal" data-calc-modal role="dialog" aria-modal="true" aria-label="درخواست برآورد دقیق">
            <form class="calc-modal__box" action="{{ route('contact.send') }}" method="POST" data-calc-form>
                @csrf
                <button type="button" class="modal__close" data-close aria-label="بستن"><i class="ri-close-line"></i></button>
                <h3>درخواست برآورد دقیق</h3>
                <p class="muted">کارشناسان ما با بررسی جزئیات، برآورد دقیق و مکتوب برای شما ارسال می‌کنند.</p>
                <div class="calc-modal__summary" data-summary></div>
                <input class="hp-field" type="text" name="website" tabindex="-1" autocomplete="off">
                <input type="hidden" name="subject" value="درخواست برآورد هزینه ساخت">
                <input type="hidden" name="body" data-body>
                <div class="form-grid">
                    <div class="field"><input type="text" id="cm-name" name="name" placeholder=" " required><label for="cm-name">نام و نام خانوادگی</label></div>
                    <div class="field"><input type="tel" id="cm-phone" name="phone" placeholder=" " required dir="ltr" style="text-align:right"><label for="cm-phone">شماره تماس</label></div>
                </div>
                <div class="alert" data-calc-alert hidden></div>
                <button type="submit" class="btn btn--block" style="margin-top:16px">ارسال درخواست <i class="ri-send-plane-line"></i></button>
            </form>
        </div>
    </div>
</section>
@endif
