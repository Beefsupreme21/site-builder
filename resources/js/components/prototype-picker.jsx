import { useCallback, useEffect, useLayoutEffect, useRef, useState } from 'react';

/** Verbatim from emilkowalski/skills prototype PICKER.md */
const pickerStyles = `
.proto-picker {
  position: fixed;
  bottom: 24px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 2147483647;
  display: flex;
  align-items: center;
  gap: 2px;
  padding: 4px;
  border-radius: 999px;
  background: rgba(10, 10, 10, 0.82);
  -webkit-backdrop-filter: blur(12px) saturate(1.4);
  backdrop-filter: blur(12px) saturate(1.4);
  box-shadow:
    0 0 0 1px rgba(255, 255, 255, 0.08) inset,
    0 8px 24px rgba(0, 0, 0, 0.24),
    0 2px 6px rgba(0, 0, 0, 0.12);
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  font-size: 13px;
  line-height: 1;
  -webkit-font-smoothing: antialiased;
  user-select: none;
  -webkit-user-select: none;
}

.proto-picker-highlight {
  position: absolute;
  top: 4px;
  left: 0;
  height: 28px;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.12);
  will-change: transform;
}

.proto-picker[data-ready] .proto-picker-highlight {
  transition:
    transform 250ms cubic-bezier(0.23, 1, 0.32, 1),
    width 250ms cubic-bezier(0.23, 1, 0.32, 1);
}

@media (prefers-reduced-motion: reduce) {
  .proto-picker[data-ready] .proto-picker-highlight { transition: none; }
}

.proto-picker-item {
  position: relative;
  display: flex;
  align-items: center;
  height: 28px;
  padding: 0 12px;
  border: 0;
  border-radius: 999px;
  background: transparent;
  color: rgba(255, 255, 255, 0.55);
  font: inherit;
  cursor: pointer;
  transition: color 150ms ease-out;
}

.proto-picker-item:hover {
  color: rgba(255, 255, 255, 0.85);
}

.proto-picker-item:active {
  transform: scale(0.97);
}

.proto-picker-item:focus-visible {
  outline: 2px solid rgba(255, 255, 255, 0.4);
  outline-offset: 2px;
}

.proto-picker-item[data-active] {
  color: #fff;
}

.proto-picker-divider {
  width: 1px;
  height: 16px;
  margin: 0 4px;
  background: rgba(255, 255, 255, 0.12);
}

.proto-picker-replay {
  padding: 0 10px;
  font-size: 14px;
}

.proto-picker[data-position="top"] {
  bottom: auto;
  top: 24px;
}
`;

export function PrototypePicker({
    variants,
    activeIndex,
    onChange,
    onReplay,
    showReplay = false,
    position = 'bottom',
}) {
    const navRef = useRef(null);
    const highlightRef = useRef(null);
    const itemRefs = useRef([]);
    const [ready, setReady] = useState(false);

    const moveHighlight = useCallback(() => {
        const el = itemRefs.current[activeIndex];
        const highlight = highlightRef.current;

        if (!el || !highlight) {
            return;
        }

        highlight.style.width = `${el.offsetWidth}px`;
        highlight.style.transform = `translateX(${el.offsetLeft}px)`;
    }, [activeIndex]);

    const setActive = useCallback(
        (index) => {
            if (index < 0 || index >= variants.length) {
                return;
            }

            onChange(index);

            const url = new URL(window.location.href);
            url.searchParams.set('v', String(index + 1));
            window.history.replaceState(null, '', url);
        },
        [onChange, variants.length],
    );

    useLayoutEffect(() => {
        moveHighlight();
    }, [moveHighlight, variants, activeIndex]);

    useEffect(() => {
        const frame = requestAnimationFrame(() => {
            requestAnimationFrame(() => setReady(true));
        });

        return () => cancelAnimationFrame(frame);
    }, []);

    useEffect(() => {
        function onResize() {
            moveHighlight();
        }

        window.addEventListener('resize', onResize);

        return () => window.removeEventListener('resize', onResize);
    }, [moveHighlight]);

    useEffect(() => {
        function onKeyDown(event) {
            const target = event.target;

            if (
                target instanceof HTMLInputElement ||
                target instanceof HTMLTextAreaElement ||
                target instanceof HTMLSelectElement ||
                target?.isContentEditable
            ) {
                return;
            }

            if (event.metaKey || event.ctrlKey || event.altKey) {
                return;
            }

            const num = parseInt(event.key, 10);

            if (num >= 1 && num <= variants.length) {
                event.preventDefault();
                setActive(num - 1);
            } else if (event.key === 'ArrowRight') {
                event.preventDefault();
                setActive((activeIndex + 1) % variants.length);
            } else if (event.key === 'ArrowLeft') {
                event.preventDefault();
                setActive((activeIndex - 1 + variants.length) % variants.length);
            } else if (
                showReplay &&
                onReplay &&
                (event.key === 'r' || event.key === 'R')
            ) {
                event.preventDefault();
                onReplay();
            }
        }

        document.addEventListener('keydown', onKeyDown);

        return () => document.removeEventListener('keydown', onKeyDown);
    }, [activeIndex, onReplay, setActive, showReplay, variants.length]);

    if (variants.length === 0) {
        return null;
    }

    return (
        <>
            <style>{pickerStyles}</style>
            <nav
                ref={navRef}
                className="proto-picker"
                data-ready={ready ? '' : undefined}
                data-position={position === 'top' ? 'top' : undefined}
                aria-label="Prototype variants"
            >
                <span
                    ref={highlightRef}
                    className="proto-picker-highlight"
                    aria-hidden="true"
                />
                {variants.map((variant, index) => (
                    <button
                        key={`${variant.name}-${index}`}
                        ref={(el) => {
                            itemRefs.current[index] = el;
                        }}
                        type="button"
                        className="proto-picker-item"
                        {...(index === activeIndex
                            ? { 'data-active': true, 'aria-current': 'true' }
                            : {})}
                        title={variant.axis}
                        onClick={() => setActive(index)}
                    >
                        {variant.name}
                    </button>
                ))}
                {showReplay && onReplay && (
                    <>
                        <span
                            className="proto-picker-divider"
                            aria-hidden="true"
                        />
                        <button
                            type="button"
                            className="proto-picker-item proto-picker-replay"
                            aria-label="Replay animation (R)"
                            onClick={onReplay}
                        >
                            ↻
                        </button>
                    </>
                )}
            </nav>
        </>
    );
}

/** Read ?v= from URL for initial picker index (1-based). */
export function initialVariantIndexFromUrl(variantCount) {
    const param = parseInt(
        new URLSearchParams(window.location.search).get('v') ?? '1',
        10,
    );

    if (Number.isNaN(param) || param < 1 || param > variantCount) {
        return 0;
    }

    return param - 1;
}
