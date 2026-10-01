<?php

test('that true is true', function () {
    expect(true)->toBeTrue(); // @phpstan-ignore pest.expectation.redundant
});
