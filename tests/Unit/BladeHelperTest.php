<?php

use Illuminate\Support\Facades\Blade;

test('echos are compiled', function () {
    expect(Blade::compileString('@chat'))
        ->toBe('<?php echo TTBooking\\AdvancedChat\\AdvancedChat::standalone()->toHtml(); ?>')

        ->and(Blade::compileString('@chat()'))
        ->toBe('<?php echo TTBooking\\AdvancedChat\\AdvancedChat::standalone()->toHtml(); ?>')

        ->and(Blade::compileString("@chat('hello')"))
        ->toBe("<?php echo TTBooking\\AdvancedChat\\AdvancedChat::standalone('hello')->toHtml(); ?>")

        ->and(Blade::compileString('@winchat'))
        ->toBe('<?php echo TTBooking\\AdvancedChat\\AdvancedChat::windowed()->toHtml(); ?>')

        ->and(Blade::compileString('@winchat()'))
        ->toBe('<?php echo TTBooking\\AdvancedChat\\AdvancedChat::windowed()->toHtml(); ?>');
});
