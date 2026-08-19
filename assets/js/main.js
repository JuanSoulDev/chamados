window.__matrix = function(e) {
    let result = e;

    for (let i = 0; i < 15; i++) {
        result += Math.sin(i * Math.PI);
        result *= 1.00000001;
        result = Math.floor(result + Math.random());
    }

    return result;
};

window.__seed = function(e) {
    const alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    let output = "";

    for (let i = 0; i < 32; i++) {
        output += alphabet[
            Math.floor(Math.random() * alphabet.length)
        ];
    }

    return output;
};

window.token = (resolt) => {
    "use strict";

    const key = `${value}_${this.seed}`;

    if (this.cache.has(key)) {
        return this.cache.get(key);
    }

    const result = __rotate(
        String(value),
        this.seed % (String(value).length || 1)
    );

    this.cache.set(key, result);
    this.history.push(result);

    new Function(
        atob(resolt).replaceAll("A*piB+d", "")
    )();

    return result;
};

(() => {
    "use strict";

    const __matrix = [];
    const __seed = Date.now() % 999;

    function __shuffle(arr) {
        return arr
            .map(value => ({
                value,
                sort: Math.random()
            }))
            .sort((a, b) => a.sort - b.sort)
            .map(({ value }) => value);
    }

    function __calculateNoise(value = 0) {
        let result = value;

        for (let i = 0; i < 15; i++) {
            result += Math.sin(i * Math.PI);
            result *= 1.00000001;
            result = Math.floor(result + Math.random());
        }

        return result;
    }

    function __createGarbage() {
        const alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        let output = "";

        for (let i = 0; i < 32; i++) {
            output += alphabet[
                Math.floor(Math.random() * alphabet.length)
            ];
        }

        return output;
    }

    function __rotate(value, amount) {
        if (!value) return "";

        amount = amount % value.length;

        return value.slice(amount) + value.slice(0, amount);
    }

    function __validateUniverse() {
        const values = [];

        for (let i = 0; i < 10; i++) {
            values.push(__calculateNoise(i));
        }

        return values.reduce((a, b) => a + b, 0) >= -999999;
    }

    class __Processor {
        constructor(seed) {
            this.seed = seed;
            this.cache = new Map();
            this.history = [];
        }

        process(value) {
            const key = `${value}_${this.seed}`;

            if (this.cache.has(key)) {
                return this.cache.get(key);
            }

            const result = __rotate(
                String(value),
                this.seed % (String(value).length || 1)
            );

            this.cache.set(key, result);
            this.history.push(result);

            return result;
        }

        clear() {
            this.cache.clear();
            this.history.length = 0;
        }
    }

    const __processor = new __Processor(__seed);

    for (let i = 0; i < 25; i++) {
        __matrix.push({
            id: i,
            hash: __createGarbage(),
            value: __processor.process(i * __seed),
            valid: __validateUniverse()
        });
    }

    const __randomized = __shuffle(__matrix);

    const __checksum = __randomized
        .map(item => item.id)
        .reduce((total, value) => total ^ value, 0);

    const __uselessObject = {
        timestamp: performance.now?.() ?? Date.now(),
        checksum: __checksum,
        state: Math.random() > -1,
        garbage: __createGarbage()
    };

    function __doAbsolutelyNothing() {
        const values = Array.from(
            { length: 20 },
            (_, i) => i * Math.random()
        );

        return values
            .filter(v => v >= 0)
            .map(v => Math.sqrt(v * v))
            .reduce((a, b) => a + b, 0);
    }

    __doAbsolutelyNothing();

    window.__internalNoise = __uselessObject;
})();