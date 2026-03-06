/**
 * SearchBar component for Admin Sidebar.
 */

import { __ } from '@wordpress/i18n';
import { useState, useCallback, useMemo } from '@wordpress/element';

const SearchBar = ({ items, onSelect }) => {
    const [query, setQuery] = useState('');
    const [isFocused, setIsFocused] = useState(false);

    const filteredItems = useMemo(() => {
        if (!query) return [];
        return items.filter(item =>
            item.label.toLowerCase().includes(query.toLowerCase())
        );
    }, [query, items]);

    const handleSearch = (e) => {
        setQuery(e.target.value);
    };

    return (
        <div className={`sm-search-bar ${isFocused ? 'is-focused' : ''}`}>
            <div className="sm-search-bar__input-wrapper">
                <span className="sm-search-bar__icon">🔍</span>
                <input
                    type="text"
                    className="sm-search-bar__input"
                    placeholder={__('Search settings...', 'smooth-maintenance')}
                    value={query}
                    onChange={handleSearch}
                    onFocus={() => setIsFocused(true)}
                    onBlur={() => setTimeout(() => setIsFocused(false), 200)}
                />
            </div>

            {isFocused && filteredItems.length > 0 && (
                <ul className="sm-search-bar__results">
                    {filteredItems.map((item) => (
                        <li
                            key={item.id}
                            className="sm-search-bar__result-item"
                            onClick={() => {
                                onSelect(item.id);
                                setQuery('');
                            }}
                        >
                            <span className="sm-search-bar__result-icon">{item.icon}</span>
                            <span className="sm-search-bar__result-label">{item.label}</span>
                        </li>
                    ))}
                </ul>
            )}
        </div>
    );
};

export default SearchBar;
