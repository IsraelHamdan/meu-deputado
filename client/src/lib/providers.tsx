"use client";
import { Provider } from "@/components/ui/provider";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { ReactQueryDevtools } from "@tanstack/react-query-devtools";
import {
  persistQueryClient,
  PersistQueryClientProvider,
} from "@tanstack/react-query-persist-client";
import { ReactNode, useEffect, useState } from "react";
import { types } from "util";

const CACHE_KEY = "REACT_QUERY_OFFLINE_CACHE";

interface QueryProviderProps {
  children: ReactNode;
}

export default function Providers({ children }: QueryProviderProps) {
  const [queryClient] = useState(
    () =>
      new QueryClient({
        defaultOptions: {
          queries: {
            staleTime: 60 * 1000,
            gcTime: 1000 * 60 * 60 * 24,
          },
        },
      })
  );

  useEffect(() => {
    if (typeof window !== "undefined") {
      persistQueryClient({
        queryClient: queryClient,
        persister: {
          persistClient: async (client) => {
            try {
              const data = JSON.stringify(client);
              sessionStorage.setItem(CACHE_KEY, data);
            } catch (err) {
              console.warn(`Failed to persist query client: ${err}`);
            }
          },
          restoreClient: async () => {
            try {
              const data = sessionStorage.getItem(CACHE_KEY);
              return data ? JSON.parse(data) : undefined;
            } catch (err) {
              console.warn("Failed to restore query client:", err);
              return undefined;
            }
          },
          removeClient: async () => {
            try {
              sessionStorage.removeItem(CACHE_KEY);
            } catch (err) {
              console.warn("Failed to remove query client:", err);
            }
          },
        },
        maxAge: 1000 * 60 * 60 * 24,
      });
    }
  });

  return (
    <QueryClientProvider client={queryClient}>
      <Provider>{children}</Provider>
      <ReactQueryDevtools initialIsOpen={false} buttonPosition="bottom-left" />
    </QueryClientProvider>
  );
}
