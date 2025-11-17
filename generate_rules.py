import pandas as pd
from itertools import combinations
import ast
import os

# -----------------------------------------------------------
# Parameter Algoritma
# -----------------------------------------------------------
MIN_SUPPORT = 0.05      # Support minimal (5%)
MIN_CONFIDENCE = 0.70   # Confidence minimal (70%)

# -----------------------------------------------------------
# Fungsi Baca CSV & Bersihkan Data
# -----------------------------------------------------------
def get_transactions_from_csv(file_path):
    """
    Membaca file CSV dan mengubah kolom PRODUK dari string list menjadi list Python.
    """
    try:
        df = pd.read_csv(file_path)
        df['PRODUK'] = df['PRODUK'].apply(lambda x: ast.literal_eval(x) if isinstance(x, str) else x)
        
        # Membersihkan spasi ekstra di setiap produk
        transactions = [
            [item.strip() for item in transaction] for transaction in df['PRODUK'].tolist()
        ]
        return transactions
    except FileNotFoundError:
        print(f"Error: File '{file_path}' tidak ditemukan.")
        return []
    except Exception as e:
        print(f"Error saat memproses file CSV: {e}")
        return []

# -----------------------------------------------------------
# Fungsi Apriori - Cari Frequent Itemsets
# -----------------------------------------------------------
def find_frequent_itemsets(transactions, min_support):
    """Mencari frequent itemsets secara manual dengan algoritma Apriori."""
    item_counts = {}
    for transaction in transactions:
        for item in transaction:
            item_counts[item] = item_counts.get(item, 0) + 1
    
    total_transactions = len(transactions)
    frequent_itemsets = {}
    
    # L1: frequent 1-itemsets
    frequent_1_itemsets = {
        frozenset([item]): count / total_transactions 
        for item, count in item_counts.items() 
        if count / total_transactions >= min_support
    }
    frequent_itemsets.update(frequent_1_itemsets)
    
    k = 2
    current_frequent_itemsets = frequent_1_itemsets
    while current_frequent_itemsets:
        candidate_itemsets = set()
        frequent_items_list = list(current_frequent_itemsets.keys())
        for i in range(len(frequent_items_list)):
            for j in range(i + 1, len(frequent_items_list)):
                itemset1 = frequent_items_list[i]
                itemset2 = frequent_items_list[j]
                new_itemset = itemset1.union(itemset2)
                if len(new_itemset) == k:
                    is_frequent_subset = True
                    for subset in combinations(new_itemset, k-1):
                        if frozenset(subset) not in current_frequent_itemsets:
                            is_frequent_subset = False
                            break
                    if is_frequent_subset:
                        candidate_itemsets.add(new_itemset)
        
        next_frequent_itemsets = {}
        for candidate in candidate_itemsets:
            count = 0
            for transaction in transactions:
                if candidate.issubset(set(transaction)):
                    count += 1
            support = count / total_transactions
            if support >= min_support:
                next_frequent_itemsets[candidate] = support
        
        frequent_itemsets.update(next_frequent_itemsets)
        current_frequent_itemsets = next_frequent_itemsets
        k += 1
        
    return frequent_itemsets

# -----------------------------------------------------------
# Fungsi Buat Association Rules tanpa duplikat arah
# -----------------------------------------------------------
def generate_association_rules(frequent_itemsets, min_confidence):
    """Menggenerasi association rules tanpa duplikat arah (A→B / B→A)."""
    rules = []
    seen_itemsets = set()  # Menyimpan kombinasi unik tanpa urutan

    for itemset in frequent_itemsets:
        if len(itemset) > 1:
            for i in range(1, len(itemset)):
                for antecedent_tuple in combinations(itemset, i):
                    antecedent = frozenset(antecedent_tuple)
                    consequent = itemset - antecedent
                    
                    # Identitas unik kombinasi
                    combined_items = frozenset(antecedent.union(consequent))
                    if combined_items in seen_itemsets:
                        continue

                    support_ab = frequent_itemsets[itemset]
                    support_a = frequent_itemsets.get(antecedent, 0)
                    support_b = frequent_itemsets.get(consequent, 0)

                    if support_a > 0:
                        confidence = support_ab / support_a
                        
                        if confidence >= min_confidence:
                            lift = confidence / support_b if support_b > 0 else 0
                            
                            rules.append({
                                'antecedents': antecedent,
                                'consequents': consequent,
                                'support': support_ab,
                                'confidence': confidence,
                                'lift': lift
                            })
                            seen_itemsets.add(combined_items)
    return rules

# -----------------------------------------------------------
# Fungsi Hitung Diskon Dinamis
# -----------------------------------------------------------
def calculate_dynamic_discount(rules, transactions):
    """Menghitung persentase diskon dinamis berdasarkan frekuensi produk."""
    all_products = [item for sublist in transactions for item in sublist]
    product_freq = pd.Series(all_products).value_counts().to_dict()
    
    max_freq = max(product_freq.values()) if product_freq else 1
    min_freq = min(product_freq.values()) if product_freq else 1

    def assign_discount(consequents):
        conseq_products = list(consequents)
        if not conseq_products:
            return 0
        
        freqs = [product_freq.get(p, 0) for p in conseq_products]
        avg_freq = sum(freqs) / len(freqs)
        
        if max_freq == min_freq:
            return 5
        normalized = (max_freq - avg_freq) / (max_freq - min_freq)
        return round(5 + normalized * 50)
    
    for rule in rules:
        rule['discount_percent'] = assign_discount(rule['consequents'])
        
    return rules

# -----------------------------------------------------------
# Main Process
# -----------------------------------------------------------
if __name__ == "__main__":
    print("Mulai proses implementasi Apriori manual...")
    
    input_path = 'storage/app/transactions/transaction_data.csv'
    output_dir = 'storage/app/association_rules'
    output_path = os.path.join(output_dir, 'association_rules_result.csv')

    os.makedirs(output_dir, exist_ok=True)
    
    transactions = get_transactions_from_csv(input_path)
    if not transactions:
        print("Proses dihentikan karena data tidak dapat dimuat.")
    else:
        print(f"Total transaksi yang diproses: {len(transactions)}")

        # 1. Cari frequent itemsets
        frequent_itemsets = find_frequent_itemsets(transactions, MIN_SUPPORT)
        print(f"Ditemukan {len(frequent_itemsets)} frequent itemsets")

        # 2. Buat association rules tanpa duplikat arah
        rules = generate_association_rules(frequent_itemsets, MIN_CONFIDENCE)
        print(f"Ditemukan {len(rules)} aturan asosiasi (tanpa rule terbalik)")

        # 3. Hitung diskon dinamis
        final_rules = calculate_dynamic_discount(rules, transactions)

        # 4. Simpan hasil
        if final_rules:
            for rule in final_rules:
                rule['antecedents'] = ', '.join(sorted(list(rule['antecedents'])))
                rule['consequents'] = ', '.join(sorted(list(rule['consequents'])))
            
            df_rules = pd.DataFrame(final_rules)
            df_rules = df_rules.sort_values(by='lift', ascending=False)
            df_rules.to_csv(output_path, index=False)
            print(f"\n{len(df_rules)} aturan asosiasi berhasil disimpan ke '{output_path}'")
        else:
            print("\nTidak ada aturan yang memenuhi kriteria.")
