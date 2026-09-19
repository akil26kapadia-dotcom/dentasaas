<style>
    .app-shell { height: 100vh; height: 100dvh; }
    .app-tabbar { position: relative; flex-shrink: 0; display: flex; align-items: flex-end; justify-content: space-around;
        padding: 0.4rem 0.5rem calc(0.4rem + env(safe-area-inset-bottom)); background: #fff; border-top: 1px solid #e5e7eb;
        box-shadow: 0 -8px 24px -14px rgba(15, 23, 42, 0.25); }
    .app-tab { position: relative; z-index: 36; flex: 1; display: flex; flex-direction: column; align-items: center; gap: 0.2rem; padding: 0.4rem 0;
        font-size: 0.68rem; font-weight: 500; color: #6b7280; background: none; border: 0; cursor: pointer; text-decoration: none; -webkit-tap-highlight-color: transparent; }
    .app-tab i { font-size: 1.15rem; }
    .app-tab.is-active { color: var(--tab-accent, #465fff); }
    .app-tab.is-active::before { content: ""; position: absolute; top: -0.4rem; width: 1.75rem; height: 3px; border-radius: 0 0 4px 4px; background: var(--tab-accent, #465fff); }
    .app-tab-fab { margin-top: -1.7rem; }
    .app-fab { display: flex; align-items: center; justify-content: center; width: 3.3rem; height: 3.3rem; border-radius: 9999px; color: #fff; font-size: 1.15rem;
        background-color: var(--tab-accent, #465fff); background-image: linear-gradient(160deg, rgba(255,255,255,0.28), rgba(255,255,255,0) 55%);
        box-shadow: 0 10px 22px -6px rgba(15, 23, 42, 0.4), 0 0 0 4px #fff; }
    .app-fab i { transition: transform 0.2s ease; }
    .app-fab.is-open i { transform: rotate(45deg); }

    .app-sheet-backdrop { position: fixed; inset: 0; z-index: 35; background: rgba(15, 23, 42, 0.4); }
    .app-sheet { position: fixed; z-index: 37; left: 0; right: 0; margin-inline: auto; width: min(92vw, 22rem);
        bottom: calc(5.6rem + env(safe-area-inset-bottom)); padding: 1rem; background: #fff; border-radius: 1.25rem; box-shadow: 0 24px 50px -12px rgba(15, 23, 42, 0.45); }
    .app-sheet-anim { transition: transform 0.22s ease, opacity 0.22s ease; }
    .app-sheet-from { transform: translateY(16px) scale(0.98); opacity: 0; }
    .app-sheet-to { transform: none; opacity: 1; }
    .app-sheet-title { margin: 0 0 0.75rem; font-size: 0.7rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: #9ca3af; }
    .app-sheet-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.6rem; }
    .app-sheet-tile { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; padding: 0.8rem 0.4rem; border-radius: 1rem; background: #f9fafb;
        font-size: 0.75rem; font-weight: 500; color: #374151; text-align: center; text-decoration: none; }
    .app-sheet-tile:active { background: #f3f4f6; }
    .app-sheet-tile span { display: flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; border-radius: 9999px; font-size: 1rem; }


    .nav-badge { margin-left: auto; display: inline-flex; align-items: center; justify-content: center; min-width: 1.25rem; height: 1.25rem; padding: 0 0.4rem;
        border-radius: 9999px; background: #f97316; color: #fff; font-size: 0.7rem; font-weight: 600; line-height: 1; }
    .app-tab .tab-badge { position: absolute; top: 0.1rem; left: calc(50% + 0.35rem); min-width: 1.05rem; height: 1.05rem; padding: 0 0.3rem; display: flex; align-items: center;
        justify-content: center; border-radius: 9999px; background: #f97316; color: #fff; font-size: 0.62rem; font-weight: 700; line-height: 1; box-shadow: 0 0 0 2px #fff; }

    @media (min-width: 1024px) { .app-tabbar { display: none; } }
</style>
