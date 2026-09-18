import { useEffect, useRef, useState } from 'react';

type Props = {
    deadline: string;
    serverNow: string;
    onExpire: () => void;
};

export default function Timer({ deadline, serverNow, onExpire }: Props) {
    const offsetRef = useRef(Date.now() - Date.parse(serverNow));
    const expiredRef = useRef(false);
    const [remaining, setRemaining] = useState(() =>
        secondsRemaining(deadline, offsetRef.current),
    );

    useEffect(() => {
        offsetRef.current = Date.now() - Date.parse(serverNow);
        expiredRef.current = false;
        setRemaining(secondsRemaining(deadline, offsetRef.current));

        const interval = window.setInterval(() => {
            const next = secondsRemaining(deadline, offsetRef.current);
            setRemaining(next);

            if (next <= 0 && !expiredRef.current) {
                expiredRef.current = true;
                onExpire();
            }
        }, 250);

        return () => window.clearInterval(interval);
    }, [deadline, serverNow, onExpire]);

    const isUrgent = remaining <= 10;

    return (
        <div
            className={`rounded-lg border px-4 py-3 text-center ${
                isUrgent
                    ? 'border-destructive/40 bg-destructive/10 text-destructive'
                    : 'bg-muted/40'
            }`}
        >
            <p className="text-muted-foreground text-sm">الوقت المتبقي</p>
            <p className="text-3xl font-semibold tabular-nums">{remaining}</p>
            <p className="text-muted-foreground text-xs">ثانية</p>
        </div>
    );
}

function secondsRemaining(deadline: string, offset: number): number {
    const remainingMs = Date.parse(deadline) - (Date.now() - offset);

    return Math.max(0, Math.ceil(remainingMs / 1000));
}
