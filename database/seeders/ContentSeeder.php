<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slide;
use App\Models\Stat;
use App\Models\Step;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

/**
 * محتوای نمونه سایت. همه موارد از پنل مدیریت قابل ویرایش هستند.
 */
class ContentSeeder extends Seeder
{
    protected function img(string $name): string
    {
        return 'assets/img/demo/'.$name.'.jpg';
    }

    public function run(): void
    {
        $this->settings();
        $this->slides();
        $this->services();
        $this->projects();
        $this->posts();
        $this->products();
        $this->people();
        $this->misc();
    }

    protected function settings(): void
    {
        $settings = [
            'site_title' => 'گروه ساختمانی طهورنیان',
            'established_year' => '۱۳۸۴',
            'experience_years' => '20',
            'working_hours' => 'شنبه تا پنجشنبه ۸:۰۰ تا ۱۸:۰۰',
            'meta_description' => 'گروه ساختمانی طهورنیان؛ مجری تخصصی طراحی معماری، ساخت، بازسازی و مدیریت پروژه‌های مسکونی، تجاری و اداری با بیش از دو دهه تجربه.',
            'meta_keywords' => 'ساختمان سازی, پیمانکاری, طراحی معماری, بازسازی, مدیریت پروژه, طهورنیان',
            'phone' => '021-88776655',
            'mobile' => '0912-345-6789',
            'email' => 'info@tahournian.ir',
            'address' => 'تهران، خیابان ولیعصر، بالاتر از پارک ساعی، برج نگار، طبقه ۱۲',
            'map_embed' => '',
            'instagram' => 'https://instagram.com/tahournian',
            'telegram' => 'https://t.me/tahournian',
            'whatsapp' => '989123456789',
            'linkedin' => 'https://linkedin.com/company/tahournian',
            'aparat' => '',
            'youtube' => '',
            'marquee_text' => 'طراحی معماری, ساخت و اجرا, بازسازی, مدیریت پروژه, نظارت مهندسی, طراحی داخلی',
            'about_subtitle' => 'درباره طهورنیان',
            'about_title' => 'ما رویاهای شما را با دقت مهندسی به سازه‌ای ماندگار تبدیل می‌کنیم',
            'about_text' => 'گروه ساختمانی طهورنیان با تکیه بر دو دهه تجربه در طراحی و اجرای پروژه‌های مسکونی، تجاری و اداری، همراه مطمئن شما از ایده تا تحویل کلید است. تیم ما متشکل از معماران، مهندسان سازه و مدیران پروژه‌ای است که کیفیت، ایمنی و زمان‌بندی دقیق را سرلوحه کار خود قرار داده‌اند.',
            'about_features' => "استفاده از مصالح استاندارد و درجه یک\nتحویل به‌موقع با قرارداد شفاف\nنظارت مهندسی در تمام مراحل\nخدمات پس از تحویل و گارانتی",
            'about_image' => $this->img('about-1'),
            'about_image_2' => $this->img('about-2'),
            'video_title' => 'نگاهی به فرایند ساخت یکی از پروژه‌های شاخص ما',
            'video_url' => '',
            'video_cover' => $this->img('video'),
            'cta_title' => 'پروژه بعدی خود را با ما آغاز کنید',
            'cta_text' => 'برای مشاوره رایگان و دریافت برآورد اولیه هزینه، همین حالا با ما در تماس باشید.',
            'about_page_text' => '<p>گروه ساختمانی طهورنیان فعالیت حرفه‌ای خود را در سال ۱۳۸۴ با اجرای پروژه‌های کوچک مسکونی آغاز کرد و امروز با تکیه بر تیمی بیش از ۸۰ نفره از متخصصان، یکی از مجریان معتبر در حوزه طراحی و ساخت است.</p><p>ما معتقدیم هر ساختمان داستانی دارد؛ داستانی که با نیاز کارفرما آغاز می‌شود و با خلاقیت معماری، دقت مهندسی و تعهد اجرایی به سرانجام می‌رسد. رویکرد ما ترکیبی از فناوری‌های نوین ساخت، مدیریت هوشمند پروژه و احترام به استانداردهای ملی و بین‌المللی است.</p>',
            'mission' => 'ساخت فضاهایی ایمن، زیبا و پایدار که کیفیت زندگی و کار را ارتقا دهند.',
            'vision' => 'تبدیل شدن به برند پیشرو در صنعت ساختمان ایران با تکیه بر نوآوری و اعتماد.',
            'values' => 'صداقت، کیفیت بی‌چون‌وچرا، احترام به زمان و سرمایه کارفرما، مسئولیت اجتماعی.',
            'page_banner' => $this->img('banner'),
            'ceo_name' => 'مهندس علی طهورنیان',
            'footer_text' => 'گروه ساختمانی طهورنیان، مجری تخصصی طراحی و ساخت پروژه‌های مسکونی، تجاری و اداری با تعهد به کیفیت و زمان.',
            'copyright' => 'تمامی حقوق این وب‌سایت متعلق به گروه ساختمانی طهورنیان است.',
            'site_tagline' => 'ساخت و انبوه سازی خانه های لوکس',
            'catalogue_title' => 'کاتالوگ پروژه ها',
            'catalogue_subtitle' => 'Projects catalogue',
            'catalogue_cover' => $this->img('p5'),
            'consult_title' => 'درخواست مشاوره',
            'consult_subtitle' => 'Request for advice',
            'consult_image' => $this->img('consult'),
            'team_text' => 'طهورنیان برای تحقق رویاهای معماری شما تلاش می کند!',
            'team_button_text' => 'فرصت‌های همکاری',
            'team_button_link' => '/contact',
            'shop_enabled' => '1',
        ];

        foreach ($settings as $key => $value) {
            Setting::put($key, $value);
        }
    }

    protected function slides(): void
    {
        $slides = [
            ['title' => 'پروژه برج پارس جردن', 'tags' => 'ساخت و ساز، انبوه سازی', 'description' => 'برج لوکس اداری تجاری پارس با مساحت ۹۰۵ متر، زیربنای ۱۷۰۰۰ متر، ۲۵ طبقه؛ ۲۰ طبقه روی زمین و ۵ طبقه زیرزمین', 'image' => $this->img('hero-2'), 'designer_avatar' => $this->img('team-10')],
            ['title' => 'ویلای مدرن لواسان', 'tags' => 'طراحی معماری، ساخت ویلا', 'description' => 'ویلای سه طبقه با نمای شیشه‌ای، استخر روباز و طراحی داخلی لوکس در زمینی به مساحت ۱۲۰۰ متر', 'image' => $this->img('p2'), 'designer_avatar' => $this->img('team-1')],
            ['title' => 'مجتمع تجاری آفتاب', 'tags' => 'تجاری، در حال ساخت', 'description' => 'مجتمع تجاری ۸ طبقه با ۱۲۰ واحد تجاری، فودکورت و پارکینگ طبقاتی در قلب شهر کرج', 'image' => $this->img('p3'), 'designer_avatar' => $this->img('team-3')],
        ];

        foreach ($slides as $i => $slide) {
            Slide::create($slide + [
                'button_text' => 'درباره این پروژه',
                'button_link' => '/projects',
                'designer_name' => 'علی طهورنیان',
                'designer_role' => 'طراح و مجری ساخت',
                'sort' => $i,
            ]);
        }
    }

    protected function services(): void
    {
        $services = [
            ['title' => 'طراحی معماری', 'icon' => 'ri-pencil-ruler-2-line', 'image' => 'p3', 'summary' => 'طراحی مفهومی، نقشه‌های اجرایی و مدل‌سازی سه‌بعدی متناسب با نیاز و بودجه شما.'],
            ['title' => 'ساخت و اجرا', 'icon' => 'ri-building-2-line', 'image' => 'p7', 'summary' => 'اجرای کامل اسکلت، سفت‌کاری و نازک‌کاری با مدیریت دقیق زمان و هزینه.'],
            ['title' => 'بازسازی و نوسازی', 'icon' => 'ri-hammer-line', 'image' => 'p6', 'summary' => 'بازسازی کامل واحدهای مسکونی و تجاری و تبدیل فضاهای قدیمی به مدرن.'],
            ['title' => 'طراحی داخلی', 'icon' => 'ri-sofa-line', 'image' => 'p5', 'summary' => 'طراحی و اجرای دکوراسیون داخلی لوکس با انتخاب هوشمندانه متریال و نورپردازی.'],
            ['title' => 'مدیریت پیمان', 'icon' => 'ri-file-list-3-line', 'image' => 'p4', 'summary' => 'مدیریت حرفه‌ای قراردادها، برنامه‌ریزی و کنترل پروژه از آغاز تا پایان.'],
            ['title' => 'نظارت و مشاوره فنی', 'icon' => 'ri-shield-check-line', 'image' => 'p8', 'summary' => 'نظارت مهندسی و مشاوره تخصصی برای اطمینان از کیفیت و ایمنی ساختمان.'],
        ];

        $body = '<p>تیم متخصص ما با بهره‌گیری از دانش روز و تجربه اجرای ده‌ها پروژه موفق، این خدمت را با بالاترین کیفیت و در چارچوب زمان‌بندی توافق‌شده ارائه می‌دهد.</p><h3>چرا طهورنیان؟</h3><p>شفافیت در قرارداد، گزارش‌دهی منظم پیشرفت پروژه، استفاده از مصالح استاندارد و ضمانت کیفیت اجرا از مهم‌ترین مزیت‌های همکاری با ماست.</p>';

        foreach ($services as $i => $service) {
            Service::create([
                'title' => $service['title'],
                'slug' => persian_slug($service['title']),
                'icon' => $service['icon'],
                'image' => $this->img($service['image']),
                'summary' => $service['summary'],
                'features' => "مشاوره اولیه رایگان\nبرآورد دقیق هزینه و زمان\nتیم متخصص و مجرب\nضمانت کیفیت اجرا",
                'body' => $body,
                'sort' => $i,
            ]);
        }
    }

    protected function projects(): void
    {
        $cats = collect(['مسکونی', 'تجاری', 'اداری', 'ویلایی'])->mapWithKeys(fn ($name, $i) => [
            $name => ProjectCategory::create(['name' => $name, 'slug' => persian_slug($name), 'sort' => $i])->id,
        ]);

        $projects = [
            ['title' => 'برج مسکونی الهیه', 'cat' => 'مسکونی', 'cover' => 'p1', 'location' => 'تهران، الهیه', 'area' => '۱۲٬۵۰۰ متر مربع', 'floors' => '۱۴ طبقه', 'units' => '۲۸ واحد', 'year' => '۱۴۰۲', 'status' => 'completed'],
            ['title' => 'ویلای مدرن لواسان', 'cat' => 'ویلایی', 'cover' => 'p2', 'location' => 'لواسان', 'area' => '۸۵۰ متر مربع', 'floors' => '۳ طبقه', 'year' => '۱۴۰۱', 'status' => 'completed'],
            ['title' => 'مجتمع تجاری آفتاب', 'cat' => 'تجاری', 'cover' => 'p3', 'location' => 'کرج، عظیمیه', 'area' => '۲۲٬۰۰۰ متر مربع', 'floors' => '۸ طبقه', 'year' => '۱۴۰۳', 'status' => 'in_progress'],
            ['title' => 'ساختمان اداری نگین', 'cat' => 'اداری', 'cover' => 'p4', 'location' => 'تهران، ونک', 'area' => '۶٬۴۰۰ متر مربع', 'floors' => '۱۰ طبقه', 'year' => '۱۴۰۰', 'status' => 'completed'],
            ['title' => 'پنت‌هاوس زعفرانیه', 'cat' => 'مسکونی', 'cover' => 'p5', 'location' => 'تهران، زعفرانیه', 'area' => '۶۲۰ متر مربع', 'floors' => 'دوبلکس', 'year' => '۱۴۰۲', 'status' => 'completed'],
            ['title' => 'بازسازی آپارتمان سعادت‌آباد', 'cat' => 'مسکونی', 'cover' => 'p6', 'location' => 'تهران، سعادت‌آباد', 'area' => '۲۴۰ متر مربع', 'floors' => 'یک واحد', 'year' => '۱۴۰۳', 'status' => 'completed'],
            ['title' => 'برج اداری تجاری پارس', 'cat' => 'تجاری', 'cover' => 'p7', 'location' => 'شیراز، معالی‌آباد', 'area' => '۳۱٬۰۰۰ متر مربع', 'floors' => '۱۸ طبقه', 'year' => '۱۴۰۴', 'status' => 'in_progress'],
            ['title' => 'مجموعه ویلایی نوشهر', 'cat' => 'ویلایی', 'cover' => 'hero-3', 'location' => 'نوشهر', 'area' => '۴٬۲۰۰ متر مربع', 'floors' => '۱۲ واحد', 'year' => '۱۴۰۴', 'status' => 'design'],
        ];

        $body = '<p>این پروژه با هدف خلق فضایی متفاوت، کاربردی و ماندگار طراحی و اجرا شده است. در طراحی نما از ترکیب سنگ طبیعی، شیشه دوجداره و المان‌های فلزی استفاده شده تا هویتی مدرن و در عین حال گرم به بنا ببخشد.</p><p>سازه ساختمان به صورت اسکلت بتنی با سیستم دیوار برشی اجرا شده و تمامی تأسیسات مکانیکی و الکتریکی مطابق با آخرین ویرایش مقررات ملی ساختمان طراحی و نصب گردیده است.</p><h3>ویژگی‌های شاخص</h3><ul><li>سیستم هوشمند مدیریت ساختمان (BMS)</li><li>عایق‌کاری حرارتی و صوتی کامل</li><li>لابی و مشاعات لوکس</li><li>پارکینگ و انباری اختصاصی</li></ul>';

        $gallery = ['p1', 'p2', 'p5', 'p6', 'hero-1'];

        foreach ($projects as $i => $p) {
            Project::create([
                'category_id' => $cats[$p['cat']],
                'title' => $p['title'],
                'slug' => persian_slug($p['title']),
                'client' => 'کارفرمای خصوصی',
                'location' => $p['location'],
                'area' => $p['area'],
                'floors' => $p['floors'],
                'units' => $p['units'] ?? fa_num(rand(4, 40)).' واحد',
                'year' => $p['year'],
                'status' => $p['status'],
                'summary' => 'طراحی و اجرای کامل پروژه '.$p['title'].' با رویکرد معماری معاصر، کیفیت ساخت بالا و توجه ویژه به جزئیات.',
                'body' => $body,
                'cover' => $this->img($p['cover']),
                'gallery' => array_map(fn ($g) => $this->img($g), array_values(array_diff($gallery, [$p['cover']]))),
                'is_featured' => $i < 6,
                'sort' => $i,
            ]);
        }
    }

    protected function posts(): void
    {
        $posts = [
            ['title' => '۷ نکته طلایی پیش از شروع ساخت ساختمان', 'category' => 'راهنمای ساخت', 'cover' => 'about-1'],
            ['title' => 'مقایسه اسکلت فلزی و بتنی؛ کدام بهتر است؟', 'category' => 'مهندسی', 'cover' => 'p9'],
            ['title' => 'ترندهای طراحی داخلی در سال ۱۴۰۵', 'category' => 'طراحی داخلی', 'cover' => 'p5'],
            ['title' => 'چگونه هزینه بازسازی آپارتمان را برآورد کنیم؟', 'category' => 'بازسازی', 'cover' => 'p6'],
            ['title' => 'اهمیت عایق‌کاری در کاهش مصرف انرژی ساختمان', 'category' => 'مهندسی', 'cover' => 'p3'],
            ['title' => 'معماری پایدار؛ آینده صنعت ساختمان', 'category' => 'معماری', 'cover' => 'hero-2'],
        ];

        $body = '<p>ساخت یک ساختمان، از کوچک‌ترین واحد مسکونی تا بزرگ‌ترین مجتمع تجاری، نیازمند برنامه‌ریزی دقیق و شناخت کافی از مراحل مختلف کار است. در این مقاله به مهم‌ترین نکاتی می‌پردازیم که رعایت آن‌ها می‌تواند در کیفیت نهایی و هزینه پروژه تأثیر چشمگیری داشته باشد.</p><h2>۱. مطالعه دقیق زمین و خاک</h2><p>پیش از هر اقدامی، انجام آزمایش مکانیک خاک و بررسی شرایط زمین ضروری است. نتایج این آزمایش مبنای طراحی فونداسیون و سازه خواهد بود.</p><h2>۲. انتخاب تیم طراحی و اجرای متخصص</h2><p>همکاری با تیمی که سابقه درخشان و نمونه‌کارهای قابل بررسی دارد، بخش بزرگی از ریسک‌های پروژه را کاهش می‌دهد.</p><blockquote>کیفیت هرگز تصادفی نیست؛ همیشه نتیجه تلاش هوشمندانه است.</blockquote><h2>۳. قرارداد شفاف</h2><p>تمام جزئیات شامل زمان‌بندی، نحوه پرداخت، مشخصات فنی مصالح و تعهدات طرفین باید به صورت مکتوب در قرارداد ذکر شود.</p>';

        foreach ($posts as $i => $p) {
            Post::create([
                'title' => $p['title'],
                'slug' => persian_slug($p['title']),
                'category' => $p['category'],
                'excerpt' => 'در این مقاله به بررسی جامع موضوع «'.$p['title'].'» می‌پردازیم و نکات کاربردی آن را برای کارفرمایان و علاقه‌مندان توضیح می‌دهیم.',
                'body' => $body,
                'cover' => $this->img($p['cover']),
                'author' => 'تیم تحریریه طهورنیان',
                'published_at' => now()->subDays($i * 6 + 1),
                'views' => rand(120, 2400),
            ]);
        }
    }

    protected function products(): void
    {
        $cats = collect(['دکوری', 'پذیرایی', 'نشیمن', 'روشنایی'])->mapWithKeys(fn ($name, $i) => [
            $name => ProductCategory::create(['name' => $name, 'slug' => persian_slug($name), 'sort' => $i])->id,
        ]);

        $items = [
            ['title' => 'مبل تک‌نفره طرح اسکاندیناوی', 'cat' => 'نشیمن', 'image' => 'pr-1', 'price' => 18500000, 'sale' => null],
            ['title' => 'ست قاب دیواری هنری', 'cat' => 'دکوری', 'image' => 'pr-2', 'price' => 2400000, 'sale' => 1790000],
            ['title' => 'صندلی کلاسیک کاپیتونی', 'cat' => 'پذیرایی', 'image' => 'pr-6', 'price' => 9800000, 'sale' => null],
            ['title' => 'کاناپه مخملی سه‌نفره', 'cat' => 'نشیمن', 'image' => 'pr-7', 'price' => 42000000, 'sale' => 37500000],
            ['title' => 'لوستر آویز مسی', 'cat' => 'روشنایی', 'image' => 'pr-8', 'price' => 6350000, 'sale' => null, 'from' => true],
            ['title' => 'صندلی مدرن پایه چوبی', 'cat' => 'پذیرایی', 'image' => 'pr-10', 'price' => 3200000, 'sale' => 2690000],
            ['title' => 'گلدان سرامیکی با گل خشک', 'cat' => 'دکوری', 'image' => 'pr-12', 'price' => 1350000, 'sale' => null, 'from' => true],
            ['title' => 'ست دکوراسیون نشیمن بوهو', 'cat' => 'دکوری', 'image' => 'pr-3', 'price' => null, 'sale' => null],
        ];

        foreach ($items as $i => $p) {
            Product::create([
                'category_id' => $cats[$p['cat']],
                'title' => $p['title'],
                'slug' => persian_slug($p['title']),
                'sku' => 'TH-'.(1001 + $i),
                'price' => $p['price'],
                'sale_price' => $p['sale'],
                'price_from' => $p['from'] ?? false,
                'summary' => 'محصولی باکیفیت و خوش‌طرح برای خانه‌های لوکس؛ انتخاب شده توسط طراحان داخلی طهورنیان.',
                'specs' => "جنس: چوب راش و پارچه مخمل\nابعاد: ۸۰ × ۷۵ × ۹۰ سانتی‌متر\nرنگ: مطابق تصویر\nگارانتی: ۱۲ ماه",
                'body' => '<p>این محصول با بهترین متریال و دقت بالا تولید شده و به زیبایی با دکوراسیون مدرن و کلاسیک هماهنگ می‌شود. تمامی محصولات فروشگاه طهورنیان با ضمانت اصالت و کیفیت ارسال می‌شوند.</p>',
                'image' => $this->img($p['image']),
                'gallery' => [$this->img($p['image']), $this->img('pr-5'), $this->img('pr-11')],
                'is_featured' => true,
                'sort' => $i,
            ]);
        }
    }

    protected function people(): void
    {
        $team = [
            ['name' => 'مهندس علی طهورنیان', 'position' => 'بنیان‌گذار و مدیرعامل', 'photo' => 'team-1'],
            ['name' => 'مهندس سارا محمدی', 'position' => 'مدیر طراحی معماری', 'photo' => 'team-2'],
            ['name' => 'مهندس رضا کریمی', 'position' => 'مدیر اجرایی پروژه‌ها', 'photo' => 'team-3'],
            ['name' => 'مهندس مریم احمدی', 'position' => 'طراح داخلی ارشد', 'photo' => 'team-4'],
            ['name' => 'مهندس نگار رحیمی', 'position' => 'مهندس سازه', 'photo' => 'team-5'],
            ['name' => 'مهندس امیر حسینی', 'position' => 'سرپرست کارگاه', 'photo' => 'team-6'],
            ['name' => 'مهندس الهام نوری', 'position' => 'کارشناس فروش', 'photo' => 'team-9'],
            ['name' => 'مهندس بهزاد مرادی', 'position' => 'مدیر مالی پروژه‌ها', 'photo' => 'team-8'],
        ];
        foreach ($team as $i => $m) {
            TeamMember::create([
                ...$m,
                'photo' => $this->img($m['photo']),
                'bio' => 'با بیش از ۱۵ سال سابقه در صنعت ساختمان و مدیریت پروژه‌های بزرگ.',
                'instagram' => 'https://instagram.com/',
                'linkedin' => 'https://linkedin.com/',
                'sort' => $i,
            ]);
        }

        $testimonials = [
            ['name' => 'دکتر حمید رضایی', 'position' => 'کارفرمای برج الهیه', 'avatar' => 'team-3', 'content' => 'از ابتدا تا انتهای پروژه، شفافیت و تعهد تیم طهورنیان برای ما شگفت‌انگیز بود. ساختمان دقیقا در زمان مقرر و با کیفیتی فراتر از انتظار تحویل شد.'],
            ['name' => 'خانم نیلوفر صادقی', 'position' => 'مالک ویلای لواسان', 'avatar' => 'team-2', 'content' => 'طراحی خلاقانه و توجه به جزئیات، ویلای ما را به یک اثر هنری تبدیل کرد. واقعا از انتخاب این تیم راضی هستیم.'],
            ['name' => 'مهندس کامران نوری', 'position' => 'مدیر هلدینگ پارس', 'avatar' => 'team-1', 'content' => 'مدیریت پروژه حرفه‌ای، گزارش‌های منظم و پاسخگویی سریع؛ سه ویژگی که همکاری با طهورنیان را برای ما متمایز کرد.'],
            ['name' => 'خانم شیما افشار', 'position' => 'کارفرمای بازسازی', 'avatar' => 'team-4', 'content' => 'آپارتمان قدیمی ما را در کمتر از سه ماه به فضایی مدرن و دلنشین تبدیل کردند. ممنون از تیم فوق‌العاده‌تان.'],
        ];
        foreach ($testimonials as $i => $t) {
            Testimonial::create([...$t, 'avatar' => $this->img($t['avatar']), 'rating' => 5, 'sort' => $i]);
        }
    }

    protected function misc(): void
    {
        foreach ([
            ['title' => 'سال تجربه', 'value' => 20, 'suffix' => '+', 'icon' => 'ri-award-line'],
            ['title' => 'پروژه موفق', 'value' => 180, 'suffix' => '+', 'icon' => 'ri-building-line'],
            ['title' => 'متخصص و مهندس', 'value' => 85, 'suffix' => '', 'icon' => 'ri-team-line'],
            ['title' => 'رضایت کارفرما', 'value' => 98, 'suffix' => '٪', 'icon' => 'ri-emotion-happy-line'],
        ] as $i => $s) {
            Stat::create($s + ['sort' => $i]);
        }

        foreach ([
            ['title' => 'مشاوره اولیه', 'icon' => 'ri-chat-smile-2-line', 'image' => $this->img('st-consult'), 'description' => 'جلسه مشاوره رایگان، بازدید از محل و شناخت دقیق نیازها و بودجه کارفرما.'],
            ['title' => 'طراحی مفهومی', 'icon' => 'ri-draft-line', 'image' => $this->img('about-1'), 'description' => 'تهیه طرح معماری، مدل سه‌بعدی و نقشه‌های اجرایی متناسب با سلیقه شما.'],
            ['title' => 'برآورد هزینه‌ها', 'icon' => 'ri-calculator-line', 'image' => $this->img('st-cost'), 'description' => 'برآورد دقیق و شفاف هزینه‌ها و زمان‌بندی مرحله به مرحله پروژه.'],
            ['title' => 'شروع عملیات اجرایی', 'icon' => 'ri-hammer-line', 'image' => $this->img('p8'), 'description' => 'اجرای پروژه توسط تیم متخصص با نظارت مستمر مهندسی و گزارش‌دهی منظم.'],
            ['title' => 'تکمیل و تحویل پروژه', 'icon' => 'ri-key-2-line', 'image' => $this->img('p1'), 'description' => 'تحویل کلید به همراه ضمانت‌نامه کیفیت و خدمات پس از تحویل.'],
        ] as $i => $s) {
            Step::create($s + ['sort' => $i]);
        }

        foreach ([
            ['question' => 'هزینه ساخت هر متر مربع چقدر است؟', 'answer' => 'هزینه ساخت به عوامل متعددی مانند موقعیت، نوع سازه، کیفیت مصالح و متراژ بستگی دارد. پس از بازدید و بررسی نیازهای شما، برآورد دقیق و مکتوب ارائه می‌کنیم.'],
            ['question' => 'آیا اخذ مجوزها و پروانه ساخت را هم انجام می‌دهید؟', 'answer' => 'بله، تیم حقوقی و فنی ما تمامی مراحل اخذ پروانه، پایان کار و استعلام‌های لازم را برای شما انجام می‌دهد.'],
            ['question' => 'مدت زمان ساخت یک ساختمان ۵ طبقه چقدر است؟', 'answer' => 'به طور معمول بین ۱۸ تا ۲۴ ماه، بسته به شرایط پروژه و نوع سازه. زمان‌بندی دقیق در قرارداد درج می‌شود.'],
            ['question' => 'آیا پروژه‌ها گارانتی دارند؟', 'answer' => 'بله، تمامی پروژه‌ها دارای ضمانت‌نامه کتبی کیفیت اجرا و خدمات پس از تحویل هستند.'],
            ['question' => 'امکان مشارکت در ساخت وجود دارد؟', 'answer' => 'بله، ما پروژه‌ها را به صورت پیمانکاری، مدیریت پیمان و مشارکت در ساخت انجام می‌دهیم.'],
        ] as $i => $f) {
            Faq::create($f + ['sort' => $i]);
        }

        foreach (['آرمان سازه', 'پارس بتن', 'نوین فولاد', 'کاشی آریا', 'سپهر نما', 'ایران شیشه'] as $i => $name) {
            Partner::create(['name' => $name, 'logo' => 'assets/img/partners/'.($i + 1).'.svg', 'sort' => $i]);
        }
    }
}
