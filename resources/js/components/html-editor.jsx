import CodeEditorImport from 'react-simple-code-editor';
import Prism from 'prismjs';
import 'prismjs/components/prism-markup';
import 'prismjs/themes/prism.css';

const Editor = CodeEditorImport.default ?? CodeEditorImport;

const editorClassName =
    'html-editor min-h-96 overflow-auto rounded-lg border border-neutral-200 bg-neutral-50 font-mono text-sm leading-relaxed';

function highlightHtml(code) {
    if (!Prism.languages.markup) {
        return code;
    }

    return Prism.highlight(code, Prism.languages.markup, 'markup');
}

export function HtmlEditor({ value, onChange, disabled = false }) {
    return (
        <div className={editorClassName}>
            <Editor
                value={value}
                onValueChange={onChange}
                highlight={highlightHtml}
                padding={16}
                disabled={disabled}
                textareaClassName="html-editor__textarea outline-none"
                preClassName="html-editor__pre"
                style={{
                    fontFamily:
                        'ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace',
                    minHeight: '24rem',
                }}
            />
        </div>
    );
}
