<?php

use App\Models\Setting;

use Illuminate\Database\Seeder;


class Websiteseetigseeder extends Seeder
{

	/**
     * @var array
     */
    protected $settings = [
        [
            'key'                       =>  'site_title',
            'value'                     =>  'Photography',
        ],
        [
            'key'                       =>  'copyright',
            'value'                     =>  'Copyright',
        ],
        [
            'key'                       =>  'disqus_username',
            'value'                     =>  NULL,
        ],
        [
            'key'                       =>  'site_logo',
            'value'                     =>  NULL,
        ],
        [
            'key'                       =>  'site_favicon',
            'value'                     =>  NULL,
        ],
        [
            'key'                       =>  'meta_keywords',
            'value'                     =>  NULL,
        ],
        [
            'key'                       =>  'meta_description',
            'value'                     =>  NULL,
        ],
        [
            'key'                       =>  'webmaster_email',
            'value'                     =>  NULL,
        ],
        [
            'key'                       =>  'address',
            'value'                     =>  NULL,
        ],
        [
            'key'                       =>  'phone',
            'value'                     =>  NULL,
        ],
        [
            'key'                       =>  'whatsapp',
            'value'                     =>  NULL,
        ],
        [
            'key'                       =>  'skype',
            'value'                     =>  NULL,
        ],
        [
            'key'                       =>  'facebook',
            'value'                     =>  NULL,
        ],
        [
            'key'                       =>  'twitter',
            'value'                     =>  NULL,
        ],
        [
            'key'                       =>  'instagram',
            'value'                     =>  NULL,
        ],
        [
            'key'                       =>  'youtube',
            'value'                     =>  NULL,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->settings as $index => $setting)
        {
            $result = Setting::create($setting);
            if (!$result) {
                $this->command->info("Insert failed at record $index.");
                return;
            }
        }

        $this->command->info('Inserted '.count($this->settings). ' records');
    }
}
