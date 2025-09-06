import { useState, useEffect } from 'react';
import { router } from '@inertiajs/react';

export function useRekap(initialFilters) {
    const [filters, setFilters] = useState(initialFilters);

    const handleFilterChange = (e) => {
        const { name, value } = e.target;
        setFilters(prevFilters => ({
            ...prevFilters,
            [name]: value
        }));
    };

    useEffect(() => {
        const delayDebounceFn = setTimeout(() => {
            router.get(route('admin.rekap'), filters, {
                preserveState: true,
                replace: true
            });
        }, 300);

        return () => clearTimeout(delayDebounceFn);
    }, [filters]);

    return {
        filters,
        handleFilterChange
    };
}