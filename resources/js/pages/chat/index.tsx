import { clear, store } from '@/actions/App/Http/Controllers/ChatController';
import { FormErrors } from '@/components/ui/form-errors';
import { Form, Head, Link } from '@inertiajs/react';
import { useEffect, useRef } from 'react';

export type ChatMessage = {
    role: 'user' | 'assistant';
    content: string;
};

const btnSecondary =
    'inline-flex items-center justify-center rounded-md border border-neutral-300 bg-white px-3 py-1.5 text-sm font-medium text-neutral-800 shadow-sm hover:bg-neutral-50 disabled:opacity-50';

const btnPrimary =
    'inline-flex items-center justify-center rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-neutral-800 disabled:opacity-60';

export default function ChatIndex({
    messages,
}: {
    messages: ChatMessage[];
}) {
    const endRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        endRef.current?.scrollIntoView({ behavior: 'smooth' });
    }, [messages]);

    return (
        <>
            <Head title="Chat" />
            <div className="mx-auto flex min-h-screen max-w-3xl flex-col p-6">
                <header className="mb-4 flex flex-wrap items-center justify-between gap-4">
                    <h1 className="text-2xl font-semibold text-neutral-900">
                        Chat
                    </h1>
                    <div className="flex flex-wrap items-center gap-2">
                        <Link
                            href={clear.url()}
                            method="post"
                            as="button"
                            className={btnSecondary}
                            disabled={messages.length === 0}
                        >
                            Clear history
                        </Link>
                        <Link href="/" className={btnSecondary}>
                            Home
                        </Link>
                    </div>
                </header>

                <div className="mb-4 min-h-[50vh] flex-1 space-y-4 overflow-y-auto rounded-lg border border-neutral-200 bg-white p-4">
                    {messages.length === 0 ? (
                        <p className="text-sm text-neutral-500">
                            Send a message to start. Conversation is stored in
                            your session until you clear it.
                        </p>
                    ) : (
                        messages.map((m, i) => (
                            <div
                                key={`${m.role}-${i}-${m.content.slice(0, 24)}`}
                                className={`flex ${m.role === 'user' ? 'justify-end' : 'justify-start'}`}
                            >
                                <div
                                    className={
                                        m.role === 'user'
                                            ? 'max-w-[85%] rounded-2xl rounded-br-md bg-neutral-900 px-4 py-2 text-sm text-white'
                                            : 'max-w-[85%] rounded-2xl rounded-bl-md bg-neutral-100 px-4 py-2 text-sm whitespace-pre-wrap text-neutral-900'
                                    }
                                >
                                    {m.content}
                                </div>
                            </div>
                        ))
                    )}
                    <div ref={endRef} />
                </div>

                <Form
                    {...store.form()}
                    options={{ preserveScroll: true }}
                    resetOnSuccess
                    disableWhileProcessing
                >
                    {({ errors, processing }) => (
                        <div className="flex flex-col gap-2">
                            <FormErrors
                                errors={{
                                    content: Array.isArray(errors.content)
                                        ? errors.content[0]
                                        : errors.content,
                                }}
                            />

                            <label htmlFor="chat-content" className="sr-only">
                                Message
                            </label>
                            <textarea
                                id="chat-content"
                                name="content"
                                rows={3}
                                required
                                placeholder="Type a message…"
                                className="w-full resize-none rounded-md border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-neutral-500 focus:ring-1 focus:ring-neutral-500 focus:outline-none"
                                disabled={processing}
                            />
                            <div className="flex justify-end">
                                <button
                                    type="submit"
                                    className={btnPrimary}
                                    disabled={processing}
                                >
                                    {processing ? 'Sending…' : 'Send'}
                                </button>
                            </div>
                        </div>
                    )}
                </Form>
            </div>
        </>
    );
}
