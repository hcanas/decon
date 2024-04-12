import {ref} from "vue";
import {find, forEach, remove as lodashRemove} from "lodash";

export function useCart() {
    const items = ref(Array.isArray(JSON.parse(localStorage.getItem('cart')))
                                        ? JSON.parse(localStorage.getItem('cart'))
                                        : []);

    function add(id) {
        items.value.push({
            id: id,
            qty: 1,
        });
        updateCart();
    }

    function update(id, qty) {
        forEach(items.value, (item, key) => {
            if (item.id === id) {
                items.value[key].qty = qty;
                return false;
            }
        });
        updateCart();
    }

    function remove(id) {
        lodashRemove(items.value, item => item.id === id);
        updateCart();
    }

    function clear() {
        items.value = [];
        updateCart();
    }

    function exists(id) {
        return find(items.value, x => x.id === id)
    }

    function updateCart() {
        localStorage.setItem('cart', JSON.stringify(items.value));
        dispatchEvent(new StorageEvent('storage', {
            key: 'cart',
            newValue: JSON.stringify(items.value),
        }));
    }

    return {
        items: items.value,
        add,
        update,
        remove,
        clear,
        exists,
    };
}
