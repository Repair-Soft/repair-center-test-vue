export interface StatusOption {
  value: string
  label: string
}

export const STATUS_OPTIONS: StatusOption[] = [
  { value: 'PENDING', label: 'En attente' },
  { value: 'IN_PROGRESS', label: 'En cours' },
  { value: 'WAITING_PARTS', label: 'Attente pièces' },
  { value: 'DONE', label: 'Terminé' },
  { value: 'DELIVERED', label: 'Livré' },
  { value: 'CANCELLED', label: 'Annulé' },
]

export function statusLabel(value: string): string {
  return STATUS_OPTIONS.find((o) => o.value === value)?.label ?? value
}
