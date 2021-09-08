<?php


namespace Azuriom\Plugin\Jirai\Models;


class Setting
{

    private $name;
    private $defaultValue;
    private $rules;
    private static $settings;

    public const SETTING_DISCORD_WEB_HOOK_FOR_SUGGESTIONS = 'discord_webhook_for_suggestions';
    public const SETTING_DISCORD_WEB_HOOK_FOR_BUGS = 'discord_webhook_for_bugs';
    public const SETTING_DISCORD_WEB_HOOK_FOR_CHANGELOGS = 'discord_webhook_for_changelogs';
    public const SETTING_ISSUES_PER_PAGES = 'issues_per_page';
    public const SETTING_CHANGELOGS_PER_PAGES = 'changelogs_per_page';

    /**
     * @return Setting[]
     */
    public static function getSettings(): array
    {
        return self::$settings ?? self::$settings = [
            new Setting(self::SETTING_DISCORD_WEB_HOOK_FOR_SUGGESTIONS, '', 'nullable', 'url', 'max:255'),
            new Setting(self::SETTING_DISCORD_WEB_HOOK_FOR_BUGS, '', 'nullable', 'url', 'max:255'),
            new Setting(self::SETTING_DISCORD_WEB_HOOK_FOR_CHANGELOGS, '', 'nullable', 'url', 'max:255'),
            new Setting(self::SETTING_ISSUES_PER_PAGES, '15', 'required', 'integer', 'max:100'),
            new Setting(self::SETTING_CHANGELOGS_PER_PAGES, '15', 'required', 'integer', 'max:100'),
        ];
    }

    private function __construct($name, $defaultValue, ...$rules)
    {
        $this->name = $name;
        $this->defaultValue = $defaultValue;
        $this->rules = $rules;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the default value if no one is already defined in the db
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Get key used for translation
     *
     * @return string
     */
    public function getTranslationKey(): string
    {

        return sprintf('jirai::admin.settings.%s', $this->name);
    }

    /**
     * Get key used to store setting value in the db
     *
     * @return string
     */
    public function getDbKey(): string
    {
        return sprintf('jirai.%s', $this->name);
    }

    /**
     * Get setting
     *
     * @param $string
     * @return Setting|null
     */
    public static function getSetting($string): ?Setting
    {
        foreach (self::getSettings() as $setting) {
            if ($setting->getName() == $string) {
                return $setting;
            }
        }
        return null;
    }

    /**
     * Get the setting value stored in the db
     *
     * @return string
     */
    public function getValue() {
        return setting($this->getDbKey(), $this->getDefaultValue());
    }

    /**
     * Get rules that must be validated to accept the request
     *
     * @return array
     */
    public static function getRules(): array
    {

        $rules = [];

        foreach (self::getSettings() as $setting) {
            $rules[$setting->getName()] = $setting->rules;
        }

        return $rules;
    }

    /**
     * Prefix all keys of the passed array by the key prefix
     *
     * @param $array
     * @return array
     */
    public static function prefixWithDbKey($array): array
    {

        $return = [];

        foreach ($array as $key => $value) {
            $return[sprintf('jirai.%s', $key)] = $value;
        }

        return $return;
    }

    /**
     * Return the right webhook url depending on the asked issue type
     *
     * @param $type
     * @return string
     */
    public static function getWebhookUrlFor($type): string
    {
        if ($type == JiraiIssue::TYPE_SUGGESTION) {
            return Setting::getSetting(Setting::SETTING_DISCORD_WEB_HOOK_FOR_SUGGESTIONS)->getValue();
        }

        return Setting::getSetting(Setting::SETTING_DISCORD_WEB_HOOK_FOR_BUGS)->getValue();
    }
}
